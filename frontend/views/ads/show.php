<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/7/2018
 * Time: 21:41
 */

use yii\bootstrap\Modal;
use yii\helpers\Html;

$this->title = Html::encode($ad->title);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <!--  categorty info  -->
            <div>
                <?php if ($parentCat): ?>
                    <strong><?= $parentCat ?> <i class="fa fa-arrow-right"></i> </strong><?= $category->name ?>
                <?php else: ?>
                    <strong><?= $category->name ?></strong>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h2><?= Html::encode($ad->title) ?></h2>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <span>г. <?= Html::encode($city['name_ru']) ?></span>
            <span><?= date('d.m.Y', $ad->updated_at) ?></span>
            <!--   TODO: склонения слов для (3 и тд) -->
            <span><?= Yii::$app->i18n->format(
                    '{n, plural, =0{# просмотров} one{# просмотр} few{# просмотра} many{# просмотров} other{# просмотров}}',
                    ['n' => $ad->count_view],
                    \Yii::$app->language); ?></span>
            <span>ID: <?= $ad->id ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <ul class="thumbnails">
                <li>
                    <a href="<?= Html::encode($ad->imageUrl) ?>" class="thumbnail" data-toggle="modal"
                       data-target="#lightbox">
                        <?= Html::img($ad->imageUrl ? $ad->imageUrl : '/img/placeholder.png', ['class' => 'img-responsive', 'style' => ['max-height' => '525px']]) ?>
                    </a>
                </li>
                <?php foreach ($images as $image): ?>
                    <li class="image-additional">
                        <a href="<?= Html::encode($image->getImageUrl()) ?>" class="thumbnail" data-toggle="modal"
                           data-target="#lightbox">
                            <?= Html::img($image->getImageUrl(), ['class' => 'img-responsive']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
        <div class="col-sm-4">
            <div>
                <!--                TODO: add main currency -->
                <p><strong><?= Html::encode($ad->price) ?> грн.</strong></p>
                <div>
                    <p><strong><?= Html::encode($user->firstname) ?></strong></p>
                    <?php foreach ($phones as $id => $phone): ?>
                        <p><?= Html::encode($phone) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <div></div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div>
                <p><strong>Описание:</strong></p>
                <div><?= Html::encode($ad->description) ?></div>
            </div>
        </div>
    </div>
    <?php
    Modal::begin([
//        'footer'=>Html::encode($ad->title),
        'id' => 'lightbox',
    ]); ?>
    <div class="preview-image">
        <img src="" alt=""/>
    </div>
    <?php Modal::end(); ?>
</div>

