<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/7/2018
 * Time: 21:41
 */
use yii\helpers\Html;
$this->title = Html::encode($ad->title);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <!--  categorty info  -->
            <div></div>
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
            <!--   TODO: add watch history -->
            <span>X просмотров</span>
            <span>ID: <?= $ad->id ?></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <div>
                <a href="" aria-hidden="true">
                    <img width="250px" height="250px" src="<?= Html::encode($ad->imageUrl) ?>" alt="<?= Html::encode($ad->title) ?>">
                </a>
            </div>
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
</div>
