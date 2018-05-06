<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 5/13/2018
 * Time: 23:32
 */
$this->title = 'Все объявления';
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
                'itemView' => '_all',
                'summary'=>'',
            ]) ?>
        </div>
    </div>
</div>