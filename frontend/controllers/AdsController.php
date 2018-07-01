<?php

namespace frontend\controllers;

use common\models\Ads;
use common\models\AdsImages;
use common\models\Categories;
use common\models\City;
use common\models\User;
use common\models\UserPhones;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class AdsController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'edit', 'delete'],
                'rules' => [

                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $adsQuery = Ads::find()->where(['user_id' => Yii::$app->user->id]);
        $ads = $adsQuery->all();
        $dp = new ActiveDataProvider([
            'query' => $adsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render('index', [
            'ads' => $ads,
            'dp' => $dp
        ]);
    }

    public function actionPublish($id)
    {
        $model = $this->findModel($id);
        $model->status = Ads::STATUS_MODERATING;
        if ($model->save(false))
            Yii::$app->session->setFlash('success', 'Объявление отправлено на модерацию');
        else Yii::$app->session->setFlash('error', 'Произошла ошибка, пожалуйста попробуйте еще раз!');
        return $this->redirect(['index']);
    }

    public function actionCategory($slug)
    {
        if ($slug) {
            $slug = explode('/', $slug);
            $slug = end($slug);
        } else $this->redirect(Url::to(['/ads/all']));

        $category = $this->findModelBySlug($slug);
        $categoryIds = [];
        $parentSlug = '';
        // TODO: MAKE CONDITION FOR MULTI-NESTING CATEGORIES
        if ($category->parent_id == 0) {
            $categoryIds = ArrayHelper::getColumn(Categories::find()->select('id')->where([
                'parent_id' => $category->id])->asArray()->all(), 'id');
        } else $parentSlug = Categories::findOne($category->id) ? Categories::findOne($category->parent_id)->name : '';
        $categoryIds[] = $category->id;
        $adsQuery = Ads::find()->where(['category_id' => $categoryIds, 'status' => Ads::STATUS_ACTIVE]);
        $ads = $adsQuery->all();
        $dp = new ActiveDataProvider([
            'query' => $adsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        Yii::$app->session->set('category_name',$category->name);
        Yii::$app->session->set('category_id',$category->id);
        Yii::$app->session->set('category_slug',$category->slug);
        return $this->render('category', [
            'dp' => $dp,
            'slug' => $category->name,
            'parentSlug' => $parentSlug,
        ]);
    }

    protected function findModelBySlug($slug)
    {
        if (($model = Categories::findOne(['slug' => $slug])) !== null) {
            return $model;
        } else {
            return $this->redirect(Url::to(['/']));
        }
    }

    /**
     * Creates a new Ads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ads();

        $cities = City::find()->select(['id', 'name_ru'])->where(['status' => '1'])->all();
        $user = User::findIdentity(Yii::$app->user->id);
        $phones = $user->getUserPhones()->all();
        $categories = Categories::getCategories();
        $adsImages = new AdsImages();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $user->id;

            if (Yii::$app->request->post('subcategory_id'))
                $model->category_id = Yii::$app->request->post('subcategory_id');

            if (isset(Yii::$app->request->post('UserPhones')['phone']))
                $model->phone = implode(',', Yii::$app->request->post('UserPhones')['phone']);

            if ($model->validate() && $model->save()) {
                // after ad was saved checking if image is set
                // if isset then update ad and store image
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile && $filePath = $model->upload()) {
                    $model->image = $filePath;
                    $model->update(false);
                }

                // edit additional images
                if ($images = Yii::$app->request->post('files')) {
                    $tempDir = Yii::getAlias('@webroot/uploads/temp/'.$user->id .'/');
                    $webDir = Yii::getAlias('@webroot/') . Yii::$app->urlManager->BaseUrl; // /web/

                    foreach ($images as $image) {
                        // if file not exists in upload folder and exists in temp move it to upload
                        // else its an old image do nothing
                        if (!file_exists($webDir . $image)
                            && file_exists($tempDir . $image)) {
                            $uploadDir = $webDir . 'uploads/' . $user->id .'/';
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
                                $adsImage->image = FileHelper::normalizePath('uploads/'. $user->id . '/' . $newImageName);
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


                Yii::$app->session->setFlash('success', 'Объявление успешно создано и отправлено на модерацию');
                return $this->redirect(['/ads/view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось создать объявление, пожалуйста, проверьте все вводимые данные и попробуйте еще раз!');
            }
        }
        $this->getView()->registerJsFile('/js/gallery.js', [
            'depends' => ['yii\web\YiiAsset']
        ]);
        return $this->render('create', [
            'model' => $model,
            'user' => $user,
            'phones' => ArrayHelper::map($phones, 'id', 'phone'),
            'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'userPhones' => new UserPhones(),
            'adsImages' => $adsImages
        ]);
    }

    public function actionSubcat() {
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $cat_id = $parents[0];

                // the getSubCatList function will query the database based on the
                // cat_id and return an array like below:
                // [
                //    ['id'=>'<sub-cat-id-1>', 'name'=>'<sub-cat-name1>'],
                //    ['id'=>'<sub-cat_id_2>', 'name'=>'<sub-cat-name2>']
                // ]
                return Json::encode(['output'=>Categories::getCategories($cat_id), 'selected'=>'']);
            }
        }
        return Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionView(int $id)
    {
        $ad = Ads::find()->where(['id' => $id/*, 'status' => Ads::STATUS_ACTIVE*/])->one();
        if (!empty($ad) && ($ad->user_id == Yii::$app->user->id || $ad->status == Ads::STATUS_ACTIVE)) {
            $city = $ad->getCity()->asArray()->one();
            $user = $ad->getUser()->one();
            $images = $ad->getAdsImages()->all();
            $category = $ad->getCategory()->one();
            $parentCat = $category->parent_id != 0 ? Categories::find($category->id)->select(['name'])->one()->name : '';

            $ad->processCountViewPost();
            return $this->render('show', [
                'ad' => $ad,
                'city' => $city,
                'user' => $user,
                'images' => $images,
                'category' => $category,
                'parentCat' => $parentCat,
                'phones' => explode(',', $ad->phone),
            ]);
        } else {
            return $this->redirect('/ads/all');
        }
    }

    public function actionEdit()
    {
        $adID = Yii::$app->request->get('id');
        $adID = intval($adID);
        $userID = Yii::$app->user->id;
        $ad = Ads::findOne(['id' => $adID, 'user_id' => $userID]);

        if (!empty($ad)) {
            $cities = City::find()->select(['id', 'name_ru'])->where(['status' => '1'])->all();
            $categories = Categories::find()->select(['id', 'name'])->asArray()->all();
            $adsImages = new AdsImages();


            if ($ad->load(Yii::$app->request->post())) {

                $ad->user_id = $userID;

                if (isset(Yii::$app->request->post('Ad')['phone']))
                    $ad->phone = implode(',', Yii::$app->request->post('UserPhones')['phone']);

                if ($ad->validate() && $ad->save()) {
                    // after ad was saved checking if image is set
                    // if isset then update ad and store image
                    $ad->imageFile = UploadedFile::getInstance($ad, 'imageFile');

                    if ($ad->imageFile && $filePath = $ad->upload()) {
                        if ($ad->image) FileHelper::unlink(Yii::getAlias('@root/') . $ad->image);
                        $ad->image = $filePath;
                        $ad->update(false);
                    }

                    // edit additional images
                    if ($images = Yii::$app->request->post('files')) {
                        $tempDir = Yii::getAlias('@webroot/uploads/temp/'.$userID .'/');
                        $webDir = Yii::getAlias('@webroot/') . Yii::$app->urlManager->BaseUrl; // /web/

                        foreach ($images as $image) {
                            // if file not exists in upload folder and exists in temp move it to upload
                            // else its an old image do nothing
                            if (!file_exists($webDir . $image)
                                && file_exists($tempDir . $image)) {
                                $uploadDir = $webDir . 'uploads/' . $userID .'/';
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
                                    $adsImage->ad_id = $ad->id;
                                    $adsImage->image = FileHelper::normalizePath('uploads/'. $userID . '/' . $newImageName);
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
                    $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось создать объявление, пожалуйста, проверьте все вводимые данные и попробуйте еще раз!');
                }
            }
            $this->getView()->registerJsFile('/js/gallery.js', [
                'depends' => ['yii\web\YiiAsset']
            ]);
            return $this->render('edit', [
                'ad' => $ad,
                'userPhones' => new UserPhones(),
                'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
                'categories' => ArrayHelper::map($categories, 'id', 'name'),
                'adsImages' => $adsImages
            ]);
        } else {
            return $this->redirect(['index']);
        }
    }


    /**
     * delete ad
     *
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete()
    {
        $userID = Yii::$app->user->id;
        $adID = Yii::$app->request->get('id');
        $adID = intval($adID);
        $ad = Ads::find()->where(['id' => $adID, 'user_id' => $userID])->one();
        if (!empty($ad)) {
            $ad->delete();
            return $this->redirect('/ads');
        } else {
            return $this->redirect(['index']);
        }
    }

    public function actionAll()
    {
        $adsQuery = Ads::find()->where(['status' => Ads::STATUS_ACTIVE]);
        $dp = new ActiveDataProvider([
            'query' => $adsQuery,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        Yii::$app->view->params['showCategories'] = true;

        //  get main cats
        $categories = Categories::getCategories(0);

        return $this->render('all', [
            'dp' => $dp,
            'categories' => $categories
        ]);
    }

    public function actionUpload()
    {
        if (!Yii::$app->request->isAjax) throw new NotFoundHttpException('Страница не найдена.');

        $image = UploadedFile::getInstanceByName('image');
        if ($image) {
            $dir = Yii::getAlias('@webroot/uploads/temp/'.Yii::$app->user->id .'/');
            try {
                FileHelper::createDirectory($dir);
            } catch (Exception $e) {
            }
            $path = $dir.$image->baseName.'.'.$image->extension;
            $image->saveAs($path);
            return Json::encode(['error'=>0, 'name'=>$image->name, 'url' => '/uploads/temp/'.Yii::$app->user->id .'/'.$image->name]);
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
                FileHelper::unlink(Yii::getAlias('@webroot/uploads/temp/'.Yii::$app->user->id .'/') . $filename);
                $success = true;
            } else {
                AdsImages::deleteAll(['id' => $id]);
                FileHelper::unlink(Yii::getAlias('@webroot/') . $filename);
                $success = true;
            }
        }

        return Json::encode(['success'=>$success]);

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
}
