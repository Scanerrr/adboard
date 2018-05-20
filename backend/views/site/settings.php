<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Site */
/* @var $form ActiveForm */
$this->title = 'Настройки сайта';
?>
<div class="site-settings">
    <div class="row">
        <div class="col-sm-12">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'siteName') ?>
                <?= $form->field($model, 'siteDescription') ?>

                <div class="form-group">
                    <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div><!-- site-settings -->
