<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Ads */

$this->title = 'Create Ads';
$this->params['breadcrumbs'][] = ['label' => 'Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ads-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
