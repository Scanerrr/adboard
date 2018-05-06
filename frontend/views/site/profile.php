<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 4/24/2018
 * Time: 23:01
 */

use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Профиль';
?>
<div class="site-profile-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-5">
                <?php $form = ActiveForm::begin(['id' => 'edit-form']); ?>

                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($user, 'firstname')->label('Имя') ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($user, 'lastname')->label('Фамилия') ?>
                    </div>
                </div>
                <?= $form->field($user, 'email')->textInput(['type' => 'email'])->label('Email') ?>

                <?= $form->field($user, 'city_id')->widget(Select2::className(), [
                    'language' => 'ru',
                    'data' => $cities,
                    'options' => [
                        'placeholder' => 'Выбрать город',
                    ]
                ]) ?>

                <?= $form->field($userPhones, 'phone')->widget(MultipleInput::className(), [
                    'rendererClass' => \common\widgets\CustomMultipleRenderer::className(),
                    'max'               => 3,
                    'min'               => 1, // should be at least 1 rows
                    'allowEmptyList'    => false,
                    'addButtonPosition' => MultipleInput::POS_FOOTER,
                    'addButtonOptions' => [
                        'label' => 'Добавить еще один',
                        'class' => 'btn multiple-input-list__btn js-input-plus btn-block btn multiple-input-list__btn js-input-plus btn btn-default'
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


                <div class="form-group">
                    <?= Html::submitButton('Редактровать', ['class' => 'btn btn-primary', 'name' => 'edit-profile-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

