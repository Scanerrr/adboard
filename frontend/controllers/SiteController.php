<?php
namespace frontend\controllers;

use common\models\Ads;
use common\models\AdsSearch;
use common\models\City;
use common\models\User;
use common\models\UserPhones;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'profile'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['profile'],
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        $searchModel = new AdsSearch();
        if ($searchModel->load(Yii::$app->request->post()) && $searchModel->validate()) {
           $q = Html::encode($searchModel->q);
           return $this->redirect(Yii::$app->urlManager->createUrl(['site/search', 'q' => $q]));
        }
        return true;
    }


    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dp = new ActiveDataProvider([
            'query' => Ads::find()->select(['id', 'title', 'price', 'image'])->where(['status' => Ads::STATUS_ACTIVE])->orderBy(['updated_at' => SORT_DESC])->limit(20),
            'pagination' => false,
            'sort' => ['defaultOrder' => ['updated_at'=>SORT_DESC]]
        ]);
        return $this->render('index', [
            'dp' => $dp
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    /**
     * show and edit profile info
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        $user = User::findIdentity(Yii::$app->user->identity);
        $cities = City::find(['status' => '1'])->select(['id', 'name_ru'])->all();
        $phones = $user->getUserPhones()->all();
        $userPhones = new UserPhones();

        if ($user->load(Yii::$app->request->post()) && $user->validate() && $user->save()) {

            if (isset(Yii::$app->request->post('UserPhones')['phone'])) {
                // remove old
                UserPhones::deleteAll(['user_id' => $user->id]);
                $phones = Yii::$app->request->post('UserPhones')['phone'];

                // insert new
                foreach (array_unique($phones) as $phone) {
                    $userPhone = new UserPhones();
                    $userPhone->user_id = $user->id;
                    $userPhone->phone = $phone;
                    $userPhone->save();
                }
                // TODO: check if phone(s) already exists

            }

            Yii::$app->session->setFlash('success', 'Данные успешно обновлены');
            return $this->refresh();
        }

        return $this->render('profile', [
            'user' => $user,
            'cities' => ArrayHelper::map($cities, 'id', 'name_ru'),
            'phones' => ArrayHelper::map($phones, 'id', 'phone'),
            'userPhones' => $userPhones,
        ]);
    }

    public function actionSearch()
    {
        $q = Yii::$app->request->get('q');
        $adsQuery = Ads::find()->filterWhere(['or', ['like', 'title', $q], ['like', 'description', $q]]);
        $ads = $adsQuery->all();
        $dp = new ActiveDataProvider([
        'query' => $adsQuery,
        'pagination' => [
            'pageSize' => 20,
        ],
    ]);

        return $this->render('search', [
            'ads' => $ads,
            'query' => $q,
            'dp' => $dp
        ]);
    }
}
