<?php

use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Ads */
/* @var $form ActiveForm */

$this->title = 'Подать объявление';
?>
<div class="ads-create">
    <div class="row">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-5">
                    <?= $form->field($model, 'title') ?>
                    <?= $form->field($model, 'category_id') ?>
                    <?= $form->field($model, 'city_id')->widget(Select2::className(), [
                        'language' => 'ru',
                        'data' => $cities,
                        'options' => [
                            'placeholder' => 'Выбрать город',
                            'value' => $city_id,

                        ],
                    ]) ?>
                    <?= $form->field($model, 'price') ?>
                    <?= $form->field($userPhones, 'phone')->widget(MultipleInput::className(), [
                        'rendererClass' => \common\widgets\CustomMultipleRenderer::className(),
                        'max'               => 3,
                        'min'               => 1, // should be at least 1 rows
                        'allowEmptyList'    => false,
                        'addButtonPosition' => MultipleInput::POS_FOOTER,
                        'addButtonOptions' => [
                            'label' => 'Добавить еще один',
                            'class' => 'btn multiple-input-list__btn btn-block js-input-plus btn-default'
                        ],
                        'enableError' => true,
                        'attributeOptions' => [
                            //'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'validateOnChange' => true,
                            'validateOnSubmit' => true,
                            'validateOnBlur' => false,
                        ],
                        'data' => $phones,
                        'columns' => [
                            [
                                'name'  => 'phone',
                                'type' => \yii\widgets\MaskedInput::className(),
                                'options' => [
                                    'class' => 'input-phone',
                                    'mask' => '380999999999',
                                ],
                            ],
                        ],
                    ])->hint('Номер должен быть в международном формате 380XXXXXXXXX')->label('Номер телефона') ?>
                </div>
                <div class="col-sm-3 col-sm-offset-3">
                    <div class="gallery"></div>
                    <?= $form->field($model, 'imageFile', [
                        'labelOptions' => [ 'class' => 'custom-file-upload' ]
                    ])->fileInput(['accept' => 'image/*','id'=>'main_image'])->label('<i class="fa fa-plus-circle"></i>'); ?>
                    <?= Html::button('Добавить еще одно', ['class' => 'btn  btn-block btn-default add_image']) ?>
<!--                    --><?//= $form->field($adsImages, 'image[]', ['options' => ['class' => 'sub_images', 'style' => 'display: none']])->fileInput(['accept' => 'image/*','id'=>'sub_image']); ?>
<!--                    --><?//= $form->field($model, 'image')->widget(FileInput::classname(), [
//                        'options' => ['accept' => 'image/png, image/jpg, image/jpeg'],
//                        'language' => 'ru',
//                        'pluginOptions' => [
//                            'uploadUrl' => "/ads/upload",
//
//                            'initialPreviewAsData' => true,
//                            'maxFileSize'=> 5*1024,
//                            'showRemove' => false,
//                            'showUpload' => false,
//                            'showCaption' => false,
//                            'previewFileType' => 'image',
//                            'allowedFileExtensions' => ['jpg', 'png', 'jpeg'],
//                            'allowedFileTypes' => ['image'],
//                            'allowedPreviewTypes' => ['image'],
//                            'minImageWidth' => '100%',
//                             'dropZoneEnabled' => false,
//
//                        ]
//                    ])->hint('Первое изобржаение будет использовано как основное')
//                        ->label('Выбрать изображения') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($model, 'description')->textarea(['rows'=>7]) ?>
                </div>
            </div>

                <div class="form-group">
                    <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
                </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div><!-- ads-create -->
