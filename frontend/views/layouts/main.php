<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\models\form\LoginForm;
use common\widgets\CustomNavBar;
use kartik\typeahead\Typeahead;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
$settings = Yii::$app->settings;
//echo "<pre>";
//var_dump($settings->clearCache()); die();
$controller = Yii::$app->controller;
$default_controller = Yii::$app->defaultRoute;
$isHome = (($controller->id === $default_controller) && ($controller->action->id === $controller->defaultAction)) ? true : false;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header>
    <?php
    CustomNavBar::begin([
        'brandLabel' => '<img src="//placehold.it/32/32" class="img-responsive"/>' . $settings->get('Site.siteName'),
        'description' => $settings->get('Site.siteDescription'),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Мой профиль', 'linkOptions' => ['data' => [
            'toggle' => 'modal', 'target' => '#modal-auth'
        ]]];
    } else {
        $menuItems[] = ['label' => 'Мой профиль', 'items' => [
            '<li class="dropdown-header">' . Yii::$app->user->identity->email . '</li>',
            '<li class="divider"></li>',
            ['label' => 'Объявления', 'url' => ['/ads/index']],
            ['label' => 'Настройки', 'url' => ['/site/profile']],
            '<li class="divider"></li>',
            '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Выйти',
                ['class' => 'btn btn-link btn-block logout']
            )
            . Html::endForm()
            . '</li>',
        ]];

        $menuItems[] = ['label' => 'Подать объявление', 'url' => ['/ads/create'], 'linkOptions' => ['class' => 'btn btn-default add-ad-btn']];

    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    CustomNavBar::end();

    ?>

    <nav class="nav-search-top">
        <div class="container-fluid">

            <div class="search-top">
                <div class="col-sm-9">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'navbar-form', 'role' => 'search'], 'id' => 'search-top', 'action' => '/site/search']); ?>
                    <div class="row">
                        <div class="col-sm-3">
                            <?= Typeahead::widget([
                                'name' => 'ad',
                                'value' => $this->params['search']['ad'],
                                'options' => ['placeholder' => 'Поиск...', 'class' => 'search'],
                                'pluginOptions' => [
                                    'minLength' => 2,
                                    'highlight' => true
                                ],
                                'dataset' => [
                                    [
                                        'templates' => [
                                            'notFound' =>
                                                '<div class="tt-suggestion tt-selectable">Не найдено</div>'
                                        ],
                                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
                                        'display' => 'name',
                                        //                                    'prefetch' => Url::to(['site/region-list']),
                                        'remote' => [
                                            'url' => Url::to(['site/ad-list']) . '?q=%QUERY',
                                            'wildcard' => '%QUERY'
                                        ]
                                    ]
                                ]
                            ]); ?>

                        </div>
                        <div class="col-sm-3">
                            <?= Typeahead::widget([
                                'name' => 'place',
                                'value' => $this->params['search']['cat'],
                                'id' => 'search_place',
                                'options' => ['placeholder' => 'Вся Украина', 'class' => 'search'],
                                'pluginOptions' => [
                                    'minLength' => 2,
                                    'highlight' => true
                                ],
                                'dataset' => [
                                    [
                                        'templates' => [
                                            'notFound' =>
                                                '<div class="tt-suggestion tt-selectable">Не найдено</div>'
                                        ],
                                        'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
                                        'display' => 'name',
                                        'prefetch' => Url::to(['site/region-list']),
                                        //                                    'prefetch' => $baseUrl . '/samples/countries.json',
                                        'remote' => [
                                            'url' => Url::to(['site/place-list']) . '?q=%QUERY',
                                            'wildcard' => '%QUERY'
                                        ]
                                    ]
                                ]
                            ]); ?>
                        </div>
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-default']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-sm-3">
                    <?php if (!$isHome || $showCategories): ?>
                        <?= Html::input('text', 'category_name',
                            Yii::$app->session->get('category_name') ?
                                Yii::$app->session->get('category_name') :
                                'Все рубрики', ['class' => 'form-control', 'id' => 'category_name',
                                'data' => [
                                    'target' => 'modal',
                                    'toggle' => '#modal-categories'
                                ]
                            ]) ?>
                    <?php endif; ?>
                </div>
            </div>

    </nav>

</header>

<div class="container">
    <div class="main-content">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="text-center Osnovalogo">
            Web Osnova - быстрое <a href="http://webosnova.com/development">создание сайтов</a> и <a
                    href="http://webosnova.com/promotion">продвижение</a>
        </div>
    </div>
</footer>

<?php
Modal::begin([
    'header' => '<h4 class="text-center">Выбор категории</h4>',
    'id' => 'modal-categories',
    'size' => 'modal-lg',
]); ?>

<?php Modal::end(); ?>


<?php
// AUTH MODAL
if (Yii::$app->user->isGuest) {

    Modal::begin(['id' => 'modal-auth']); ?>

    <div class="row">
        <div class="col-sm-12">

            <?= \yii\bootstrap\Tabs::widget([
                'items' => [
                    [
                        'label' => 'Вход',
                        'content' => $this->render('modalLogin', ['model' => new LoginForm]),
                        'active' => true
                    ],
                    [
                        'label' => 'Регистрация',
                        'content' => $this->render('modalSignup', ['model' => new \frontend\models\SignupForm()]),
                    ]
                ]
            ]);
            ?>
        </div>
    </div>

    <?php
    Modal::end();
} ?>

<?php $this->endBody() ?>


</body>
</html>
<?php $this->endPage() ?>
