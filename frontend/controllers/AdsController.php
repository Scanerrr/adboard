<?php

namespace frontend\controllers;

use common\models\Ads;
use common\models\AdsImages;
use common\models\City;
use common\models\User;
use common\models\UserPhones;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\HttpException;
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

    /**
     * Creating of ad
     *
     * @return string
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreate()
    {

        $model = new Ads();
        $cities = City::find(['status' => '1'])->select(['id', 'name_ru'])->all();
        $user = User::findIdentity(Yii::$app->user->identity);
        $phones = $user->getUserPhones()->all();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $user->id;

            if (isset(Yii::$app->request->post('UserPhones')['phone']))
                $model->phone = implode(',', Yii::$app->request->post('UserPhones')['phone']);

            if ($model->validate() && $model->save()) {
                $id = Yii::$app->getDb()->getLastInsertID();
                // after ad was saved checking if image is set
                // if isset then update ad and store image
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->imageFile && $filePath = $model->upload()) {
                    $model->image = $filePath;
                    $model->update(false);
                }
                Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
                $this->redirect(Url::to(['/ads/view', 'id' => $id]));
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось создать объявление, пожалуйста, проверьте все вводимые данные и попробуйте еще раз!');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'phones' => ArrayHelper::map($phones, 'id', 'phone'),
            'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
            'userPhones' => new UserPhones(),
            'city_id' => $user->city_id,
            'adsImages' => new AdsImages(), // TODO: add additional images
        ]);
    }

    public function actionView(int $id)
    {
        $ad = Ads::findOne($id);
        $city = $ad->getCity()->asArray()->one();
        $user = $ad->getUser()->one();
        $userPhones = UserPhones::find()->where(['user_id' => $user->id])->all();
        if (!empty($ad)) {
            return $this->render('show', [
                'ad' => $ad,
                'city' => $city,
                'user' => $user,
                'phones' => ArrayHelper::map($userPhones, 'id', 'phone'),
            ]);
        } else {
            return $this->redirect('/ads');
        }
    }

    public function actionEdit()
    {
        $adID = Yii::$app->request->get('id');
        $adID = intval($adID);
        $userID = Yii::$app->user->id;
        $ad = Ads::findOne(['id' => $adID, 'user_id' => $userID]);
        $cities = City::find(['status' => '1'])->select(['id', 'name_ru'])->all();
        if (!empty($ad)) {

            if ($ad->load(Yii::$app->request->post())) {
                $ad->user_id = $userID;

                if (isset(Yii::$app->request->post('Ad')['phone']))
                    $ad->phone = implode(',', Yii::$app->request->post('UserPhones')['phone']);

                if ($ad->validate() && $ad->save()) {
                    // after ad was saved checking if image is set
                    // if isset then update ad and store image
                    $ad->imageFile = UploadedFile::getInstance($ad, 'imageFile');
//                    if ($ad->image && $ad->imageFile) @unlink(Yii::getAlias('@frontend/web/' . $ad->image));
                    // TODO: delete old image

                    if ($ad->imageFile && $filePath = $ad->upload()) {
                        $ad->image = $filePath;
                        $ad->update(false);
                    }
                    Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
                    $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Не удалось создать объявление, пожалуйста, проверьте все вводимые данные и попробуйте еще раз!');
                }
            }

            return $this->render('edit', [
                'ad' => $ad,
                'userPhones' => new UserPhones(),
                'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
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
        return $this->render('all', [
            'dp' => $dp
        ]);
    }
}
