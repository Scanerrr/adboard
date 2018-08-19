<?php

namespace backend\controllers;

use common\models\AdsImages;
use common\models\Categories;
use common\models\City;
use common\models\Statuses;
use common\models\User;
use common\models\UserPhones;
use Yii;
use common\models\Ads;
use common\models\AdsSearch;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AdsController implements the CRUD actions for Ads model.
 */
class AdsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ads models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $model->status = Ads::STATUS_ACTIVE;
        if ($model->save(false))
            Yii::$app->session->setFlash('success', 'Объявление активировано');
        else Yii::$app->session->setFlash('error', 'Не удалось активировать объявление');
        return $this->redirect(['index']);
    }

//    /**
//     * Creates a new Ads model.
//     * If creation is successful, the browser will be redirected to the 'view' page.
//     * @return mixed
//     */
//    public function actionCreate()
//    {
//        $model = new Ads();
//
//        $cities = City::find()->select(['id', 'name_ru'])->where(['status' => '1'])->all();
//        $statuses = Statuses::find()->all();
//        $user = User::findIdentity(Yii::$app->user->id);
//        $phones = $user->getUserPhones()->all();
//        $categories = Categories::getCategories();
//        $adsImages = new AdsImages();
//
//        if ($model->load(Yii::$app->request->post())) {
//            $model->user_id = $user->id;
//
//            if (Yii::$app->request->post('subcategory_id'))
//                $model->category_id = Yii::$app->request->post('subcategory_id');
//
//            if (isset(Yii::$app->request->post('UserPhones')['phone']))
//                $model->phone = implode(',', Yii::$app->request->post('UserPhones')['phone']);
//
//            if ($model->validate() && $model->save()) {
//                // after ad was saved checking if image is set
//                // if isset then update ad and store image
//                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
//                if ($model->imageFile && $filePath = $model->upload()) {
//                    $model->image = $filePath;
//                    $model->update(false);
//                }
//
//                // check if additional images isset
//                $adsImages->imageFiles = UploadedFile::getInstances($adsImages, 'imageFiles');
//
//                if ($model->imageFile && $filePathes = $adsImages->upload()) {
//
//                    // TODO: check if main image and additional repeats
//                    foreach ($filePathes as $path) {
//                        $adsImage = new AdsImages();
//                        $adsImage->ad_id = $model->id;
//                        $adsImage->image = $path;
//                        $adsImage->save(false);
//                    }
//                }
//
//
//                Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
//                return $this->redirect(['index']);
//            } else {
//                Yii::$app->session->setFlash('error', 'Не удалось создать объявление, пожалуйста, проверьте все вводимые данные и попробуйте еще раз!');
//            }
//        }
//
//        return $this->render('create', [
//            'model' => $model,
//            'phones' => ArrayHelper::map($phones, 'id', 'phone'),
//            'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
//            'statuses' => ArrayHelper::map($statuses, 'id', 'name'),
//            'categories' => ArrayHelper::map($categories, 'id', 'name'),
//            'userPhones' => new UserPhones(),
//            'adsImages' => $adsImages
//        ]);
//    }

    public function actionSubcat() {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];
                $out = Categories::getCategoriesAsArray($cat_id);

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return Json::encode(['output'=>$out, 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    /**
     * Updates an existing Ads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $cities = City::find()->select(['id', 'name_ru'])->where(['status' => '1'])->all();
        $statuses = Statuses::find()->all();
        $user = User::findIdentity(Yii::$app->user->id);
        $phones = $user->getUserPhones()->all();
        $categories = Categories::find()->select(['id', 'name', 'parent_id'])->all();
        $adsImages = new AdsImages();

        if ($model->load(Yii::$app->request->post())) {

            if (isset(Yii::$app->request->post('UserPhones')['phone']))
                $model->phone = implode(',', Yii::$app->request->post('UserPhones')['phone']);

            if ($model->validate() && $model->save()) {
                // after ad was saved checking if image is set
                // if isset then update ad and store image
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile && $filePath = $model->upload()) {
                    if ($model->image) FileHelper::unlink(Yii::getAlias('@root/') . $model->image);
                    $model->image = $filePath;
                    $model->update(false);
                }

                // edit additional images
                if ($images = Yii::$app->request->post('files')) {
                    $tempDir = Yii::getAlias('@root/uploads/temp/'.$user->id .'/');
                    $webDir = Yii::getAlias('@root/'); // /web/

                    foreach ($images as $image) {
                        // if file not exists in upload folder and exists in temp move it to upload
                        // else its an old image do nothing
                        if (!file_exists($webDir . $image)
                            && file_exists($tempDir . $image)) {
                            $uploadDir = $webDir . 'uploads/' . $model->user_id .'/';
                            if (!is_dir($uploadDir)) {
                                try {
                                    FileHelper::createDirectory($uploadDir);
                                } catch (Exception $e) {
                                }
                            }
                            $pathinfo = pathinfo($image);
                            $ext = isset($pathinfo['extension']) ? $pathinfo['extension'] : '.jpg';
                            $newImageName = uniqid(time()) . '.' . $ext;
                            $imagePath = FileHelper::normalizePath($uploadDir . $newImageName);
                            if (rename($tempDir . $image, $imagePath)) {
                                $adsImage = new AdsImages();
                                $adsImage->ad_id = $model->id;
                                $adsImage->image = FileHelper::normalizePath('uploads/'. $model->user_id . '/' . $newImageName);
                                $adsImage->save(false);
                            }
                        }
                    }
                    // delete temp folder
                    try {
                        FileHelper::removeDirectory($tempDir);
                    } catch (ErrorException $e) {
                    }
                }


                Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось создать объявление, пожалуйста, проверьте все вводимые данные и попробуйте еще раз!');
            }
            return $this->redirect(['index']);
        }
        $this->getView()->registerJsFile('/js/gallery.js', [
            'depends' => ['yii\web\YiiAsset']
        ]);
        return $this->render('update', [
            'model' => $model,
            'phones' => ArrayHelper::map($phones, 'id', 'phone'),
            'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
            'statuses' => ArrayHelper::map($statuses, 'id', 'name'),
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'userPhones' => new UserPhones(),
            'adsImages' => $adsImages
        ]);
    }

    /**
     * Deletes an existing Ads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Ads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ads the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ads::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    public function actionUpload()
    {
        if (!Yii::$app->request->isAjax) throw new NotFoundHttpException('Страница не найдена.');

        $image = UploadedFile::getInstanceByName('image');
        if ($image) {
            $dir = Yii::getAlias('@root/uploads/temp/'.Yii::$app->user->id .'/');
            try {
                FileHelper::createDirectory($dir);
            } catch (Exception $e) {
            }
            $path = $dir.$image->baseName.'.'.$image->extension;
            $image->saveAs($path);
            return Json::encode(['error'=>0, 'name'=>$image->name, 'url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl('/uploads/temp/'.Yii::$app->user->id .'/'.$image->name)]);
        }

    }

    public function actionUploaddelete()
    {
        if (!Yii::$app->request->isAjax) throw new NotFoundHttpException('Страница не найдена.');

        $success = false;
        $id = Yii::$app->request->post('id');
        $filename = Yii::$app->request->post('filename');
        if ($id != null && $filename) {
            // if equal 0 then its temp file else its existed file
            if ($id == 0) {
                FileHelper::unlink(Yii::getAlias('@root/uploads/temp/'.Yii::$app->user->id .'/') . $filename);
                $success = true;
            } else {
                AdsImages::deleteAll(['id' => $id]);
                FileHelper::unlink(Yii::getAlias('@root/') . $filename);
                $success = true;
            }
        }

        return Json::encode(['success'=>$success]);

    }
}
