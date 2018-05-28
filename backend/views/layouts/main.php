<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\CustomNavBar;

use yii\helpers\Html;
use yii\bootstrap\Nav;

use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
AppAsset::register($this);
$settings = Yii::$app->settings;
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
    CustomNavBar::begin([
        'brandLabel' => $settings->get('Site.siteName'),

        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse',
        ],
    ]);
    $menuItems = [];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Вход', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'Мой профиль', 'items' => [
            '<li class="dropdown-header">' . Yii::$app->user->identity->email . '</li>',
            '<li class="divider"></li>',
            ['label' => 'Сайт', 'url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl('')],
            '<li class="divider"></li>',
            ['label' => 'Объявления', 'url' => ['/ads/index']],
            ['label' => 'Настройки', 'url' => ['/site/settings']],
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
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    CustomNavBar::end();
    ?>



    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?php if (!Yii::$app->user->isGuest): ?>
        <div class="col-sm-3"><?php
            $menuItems = [];

            $menuItems[] = ['label' => 'Категории', 'url' => ['categories/index']];
            $menuItems[] = ['label' => 'Объявления', 'url' => ['ads/index']];
            $menuItems[] = ['label' => 'Настройки', 'url' => ['site/settings']];

            echo Nav::widget([
                'options' => ['class' => ''],
                'items' => $menuItems,
            ]); ?></div>
        <div class="col-sm-9">
        <?= $content ?>
        </div>
        <?php else: ?>
        <div class="col-sm-12">
            <?= $content ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="text-center Osnovalogo">
            Web Osnova - быстрое <a href="http://webosnova.com/development">создание сайтов</a> и <a href="http://webosnova.com/promotion">продвижение</a>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>

</body>
</html>
<?php $this->endPage() ?>
