<?php
/**
 * Created by PhpStorm.
 * User: fron-
 * Date: 4/24/2018
 * Time: 23:01
 */

use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use unclead\multipleinput\TabularColumn;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'edit-form', 'action' => '/site/edit']); ?>

            <?= $form->field($user, 'firstname')->label('Имя') ?>

            <?= $form->field($user, 'lastname')->label('Фамилия') ?>

            <?= $form->field($user, 'email')->textInput(['type' => 'email'])->label('Email') ?>

            <div class="form-group">
                <label class="control-label" for="city_id">Город</label>
                <?= Select2::widget([
                    'name' => 'User[city_id]',
                    'id' => 'city_id',
                    'language' => 'ru',
                    'data' => $cities,
                    'options' => [
                        'placeholder' => 'Выбрать город',
                    ],
                    'value' => $user->city_id
                ]) ?>
            </div>
            <?= $form->field($userPhones, 'telephones')->widget(MultipleInput::className(), [
                'max'               => 3,
                'min'               => 1, // should be at least 1 rows
                'allowEmptyList'    => false,
                'attributeOptions' => [
//                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnChange' => true,
                    'validateOnSubmit' => true,
                    'validateOnBlur' => false,
                ],
                'data' => $phones


//                'enableGuessTitle'  => true,
//                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
            ])->hint('Номер должен быть в международном формате +380XXXXXXXXX')->label('Номер телефона') ?>


            <div class="form-group">
                <?= Html::submitButton('Редактровать', ['class' => 'btn btn-primary', 'name' => 'edit-profile-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>