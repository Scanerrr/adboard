<?php

namespace backend\controllers;

use Yii;
use common\models\Categories;
use common\models\CategoriesSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategoriesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = ['pageSize' => 30];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Categories();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->slug = self::translit($model->name);

            if ($model->save()) {
                // after ad was saved checking if image is set
                // if isset then update ad and store image
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile && $filePath = $model->upload()) {
                    $model->image = $filePath;
                    $model->update(false);
                }
                Yii::$app->session->setFlash('success', 'Категория успешно создана');
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка');
            }
            return $this->redirect(['index']);
        }

        $categories = Categories::getCategories();
        return $this->render('create', [
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Categories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->slug = self::translit($model->name);

            if ($model->save()) {
                // after ad was saved checking if image is set
                // if isset then update ad and store image
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile && $filePath = $model->upload()) {
                    if ($model->image) FileHelper::unlink(Yii::getAlias('@root/') . $model->image);
                    $model->image = $filePath;
                    $model->update(false);
                }
                Yii::$app->session->setFlash('success', 'Категория успешно обновлена');
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка');
            }
            return $this->redirect(['index']);
        }
        $categories = Categories::getCategories();
        return $this->render('update', [
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
        ]);
    }

    /**
     * Deletes an existing Categories model.
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
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public static function translit($text, $lowercase = true)
    {
        $text = trim($text);
        if($lowercase)
            $text = mb_strtolower($text);
        //cyrilic and symbols translit
        $replace = [
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
            'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
            'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',

            '-' => '-', ' ' => '-', '.' => '-', ',' => '-', '&' => 'and',
        ];
        $s = '';
        for ($i = 0; $i < mb_strlen($text); $i++) {
            $c = mb_substr($text, $i, 1);
            if (array_key_exists($c, $replace)) {
                $s .= $replace[$c];
            } else {
                $s .= $c;
            }
        }
        //other translit
        //make sure that you set locale for using iconv
        $s = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
        //remove symbols
        $s = preg_replace('/[^\-0-9a-z]+/i', '', $s);
        //double spaces
        $s = preg_replace('/\-+/', '-', $s);
        //spaces at begin and end
        //$s = preg_replace('/^\-*(.*?)\-*$/', '$1', $s);
        return $s;
    }
}
