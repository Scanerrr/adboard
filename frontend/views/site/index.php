<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Главная';
$text = is_object(Yii::$app->settings->get('Site.seoText')) ? serialize(Yii::$app->settings->get('Site.seoText')) : Yii::$app->settings->get('Site.seoText');
?>

<div class="top-categories">
    <div class="container">
        <div class="row">
            <div class="main-categories">
                <?php foreach ($categories as $category): ?>
                    <div class="col-sm-3">
                        <div class="main-category">
                            <?php $image = $category['image'] ? $category['image'] : '/img/categories/default.png'; ?>

                            <?= Html::img($image) ?>
                            <?= Html::a($category['name'], '#', [
                                'class' => 'get-subcategories main-subcategory-caption',
                                'data-category_id' => $category['id'],
                                'data-category_slug' => $category['slug']
                            ]) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="site-index">

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Новые объявления</h1>
            </div>
        </div>
        <div class="row">
            <?php foreach ($ads as $ad): ?>
                <div class="col-sm-3">
                    <div class="c-ad-card">
                        <div class="c-ad-image">
                            <?= Html::a(Html::img($ad->imageUrl ? $ad->imageUrl : '/img/placeholder.png', ['class' => 'small-img']), Url::to(['ads/view', 'id' => $ad->id])) ?>
                        </div>
                        <div class="c-ad-info">
                            <h3><?= Html::a(Html::encode($ad->title), Url::to(['ads/view', 'id' => $ad->id])) ?></h3>
                            <p><?= Html::encode($ad->price) ?> грн.</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="text-right">
                    <?= Html::a('Посмотреть все', Url::to(['ads/all']), ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
        <?php if ($text): ?>
            <div class="row">
                <div class="col-sm-12">
                    <h1>Seo text</h1>
                    <p><?= \yii\helpers\StringHelper::truncate(Html::encode($text), 1500) ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
