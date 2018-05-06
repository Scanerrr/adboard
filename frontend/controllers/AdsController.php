<?php

namespace frontend\controllers;

use common\models\Ads;
use common\models\City;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class AdsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $model = new Ads();
        $cities = City::find(['status' => '1'])->select(['id', 'name_ru'])->all();
//        var_dump(Yii::$app->request->post()); die();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFiles = UploadedFile::getInstance($model, 'imageFiles');
            if ($model->validate() && $model->upload()) {
                // file is uploaded successfully
                $model->save(false);
            }
            $this->redirect('../');
        }

        return $this->render('create', [
            'model' => $model,
            'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
        ]);
    }


}
