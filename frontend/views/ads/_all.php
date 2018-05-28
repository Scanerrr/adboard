<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/13/2018
 * Time: 16:53
 */

use yii\helpers\Html;
use yii\helpers\Url;
$img = $model->imageUrl ? $model->imageUrl : '/img/placeholder.png';
?>

<div class="ads-data">
    <div class="row">
        <div class="col-sm-3">
            <?= Html::a(Html::img($img, ['class' => 'small-img']), Url::to(['ads/view', 'id' => $model->id])) ?>
        </div>
        <div class="col-sm-9">
            <h3><?= Html::a(Html::encode($model->title), Url::to(['ads/view', 'id' => $model->id])) ?></h3>
            <p><?= Html::encode($model->price) ?> грн.</p>
            <p><?= \yii\helpers\StringHelper::truncate(Html::encode($model->description), 150) ?></p>
        </div>
    </div>
</div>
