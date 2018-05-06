<?php

use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $ad common\models\Ads */
/* @var $form ActiveForm */
$this->title = 'Редактировать объявление';
?>
<div class="ads-edit">

    <div class="row">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-5">
                    <?= $form->field($ad, 'title') ?>
                    <?= $form->field($ad, 'category_id') ?>
                    <?= $form->field($ad, 'city_id')->widget(Select2::className(), [
                        'language' => 'ru',
                        'data' => $cities,
                        'options' => [
                            'placeholder' => 'Выбрать город',
                        ],
                    ]) ?>
                    <?= $form->field($ad, 'price') ?>
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
                        'data' => explode(',', $ad->phone),
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
                    <div class="gallery">
                        <?php if ($ad->image): ?>
                            <img src="<?= $ad->imageUrl ?>" alt="">
                        <?php endif; ?>
                    </div>
                    <?= $form->field($ad, 'imageFile', [
                        'labelOptions' => [ 'class' => 'custom-file-upload' ]
                    ])->fileInput(['accept' => 'image/*','id'=>'main_image'])->label('<i class="fa fa-plus-circle"></i>'); ?>
                    <?= Html::button('Добавить еще одно', ['class' => 'btn  btn-block btn-default add_image']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?= $form->field($ad, 'description')->textarea(['rows'=>7]) ?>
                </div>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Создать', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div><!-- ads-edit -->
