<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/13/2018
 * Time: 16:53
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>


<div class="c-ad-card">
    <div class="c-ad-image">
        <?= Html::a(Html::img($model->imageUrl, ['class' => 'small-img']), Url::to(['ads/view', 'id' => $model->id])) ?>
    </div>
    <div class="c-ad-info">
        <h3><?= Html::a(Html::encode($model->title), Url::to(['ads/view', 'id' => $model->id])) ?></h3>
        <p><?= Html::encode($model->price) ?> грн.</p>
    </div>
</div>



