<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Ads */

$this->title = 'Создать объявление';
?>
<div class="ads-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'cities' => $cities,
        'statuses' => $statuses,
        'userPhones' => $userPhones,
        'phones' => $phones,
        'adsImages' => $adsImages,
    ]) ?>

</div>
