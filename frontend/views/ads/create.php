<?php

use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ads */
/* @var $form ActiveForm */
?>
<div class="ads-create">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'title') ?>
        <?= $form->field($model, 'category_id') ?>
        <div class="form-group">
            <label class="control-label" for="city_id">Город</label>
            <?= Select2::widget([
                'name' => 'Ads[city_id]',
                'id' => 'city_id',
                'language' => 'ru',
                'data' => $cities,
                'options' => [
                    'placeholder' => 'Выбрать город',
                ],
                'value' => $user->city_id
            ]) ?>
        </div>
        <?= $form->field($model, 'price') ?>
        <?= $form->field($model, 'telephone') ?>
        <?= $form->field($model, 'description')->textarea() ?>
        <?= $form->field($model, 'imageFiles')->widget(FileInput::classname(), [
            'options' => ['accept' => 'image/png, image/jpg, image/jpeg','multiple' => true],
            'language' => 'ru',
            'pluginOptions' => [
                'initialPreviewAsData' => true,
                'maxFileCount' => 10,
                'maxFileSize'=> 5*1024,
                'showRemove' => false,
                'showUpload' => false,
                'showCaption' => false,
                'previewFileType' => 'image',
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' =>  'Выбрать изображение',
                'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
                'allowedFileTypes' => ['image'],
                'allowedPreviewTypes' => ['image'],
                'minImageWidth' => '100%',
                // 'dropZoneEnabled' => false,

            ]
        ])->hint('Первое изобржаение будет использовано как основное')
        ->label('Выбрать изображения') ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- ads-create -->
