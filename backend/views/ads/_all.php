<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/20/2018
 * Time: 23:51
 */
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="ads-data">
    <div class="row">
        <div class="col-sm-3">
            <?= Html::a(Html::img(Yii::$app->urlManagerFrontend->createAbsoluteUrl($model->imageUrl), ['class' => 'small-img']), Url::to(['ads/view', 'id' => $model->id])) ?>
        </div>
        <div class="col-sm-6">
            <h3><?= Html::a(Html::encode($model->title), Url::to(['ads/view', 'id' => $model->id])) ?></h3>
            <p><?= Html::encode($model->price) ?> грн.</p>
            <p><?= \yii\helpers\StringHelper::truncate(Html::encode($model->description), 150) ?></p>
        </div>
        <div class="col-sm-3">
            <span><?= Html::a('Редактировать', Url::to(['/ads/update', 'id' => $model->id])) ?></span>
            <span><?= Html::a('Удалить', Url::to(['/ads/delete', 'id' => $model->id])) ?></span>
            <?php if ($model->status == $model::STATUS_ACTIVE): ?>
                <span>Опубликовано</span>
            <?php elseif ($model->status == $model::STATUS_DISABLED): ?>
                <span><?= Html::a('Опубликовать', Url::to(['/ads/publish', 'id' => $model->id])) ?></span>
            <?php else: ?>
                <span>Проверяется</span>
            <?php endif; ?>
        </div>
    </div>

</div>