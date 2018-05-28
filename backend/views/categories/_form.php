<?php

use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Categories */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="categories-form custom-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="col-sm-6">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <label class="control-label">Изображение категории</label>
        <?= $form->field($model, 'imageFile', [
            'labelOptions' => [ 'class' => 'custom-file-upload' ]
        ])->fileInput(['accept' => 'image/*','id'=>'main_image'])
            ->label($model->imageUrl
                ? Html::img(Yii::$app->urlManagerFrontend->createAbsoluteUrl($model->imageUrl))
                : '<i class="fa fa-plus-circle"></i>'); ?>

        <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
            'data' => $categories,

            'options' => [
                'placeholder' => 'Выбрать категорию',
                'value' => $model->parent_id
            ],
            'pluginOptions' => [
                    'allowClear' => true
            ]
        ])->hint('Если категория не выбрана, то созданная категория будет главной') ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
