<?php
/* @var $this yii\web\View */


$this->title = 'Мои объявления';
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1><?= $this->title ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dp,
                'itemView' => '_view',
                'summary'=>'',
            ]) ?>
        </div>
    </div>
</div>
