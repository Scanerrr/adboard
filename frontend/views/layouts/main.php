<?php

/* @var $this \yii\web\View */
/* @var $content string */

use common\models\AdsSearch;
use kartik\typeahead\Typeahead;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
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

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Регистарция', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
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
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>',
        ]];

        $menuItems[] = ['label' => 'Подать объявление', 'url' => ['/ads/create'], 'linkOptions'=>['class' => 'btn btn-default add-ad-btn']];

    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();

    ?>

    <nav class="nav-search-top">
        <div class="container">
            <div class="search-top">
                <?php $form = ActiveForm::begin(['id' => 'search-top', 'action' => '/site/search']); ?>
                <div class="row">
                    <div class="col-sm-3">
                        <?= Typeahead::widget([
                            'name' => 'ad',
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
<!--                        --><?//= Html::input('text', 'ad', '', [
//                                'class' => 'form-control', 'placeholder' =>'Поиск...']) ?>
                    </div>
                    <div class="col-sm-3">
                        <?= Typeahead::widget([
                            'name' => 'place',
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
        </div>
    </nav>
    <div class="container">

        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>

<?php $this->registerJsFile('/js/main.js',  ['position' => yii\web\View::POS_END]); ?>
</body>
</html>
<?php $this->endPage() ?>
