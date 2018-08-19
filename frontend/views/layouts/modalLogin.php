<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\form\LoginForm */


use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<?php $form = ActiveForm::begin(['action' => '/site/login', 'id' => 'modal-login-form']); ?>

<?= $form->field($model, 'email') ?>

<?= $form->field($model, 'password')->passwordInput() ?>

<?= $form->field($model, 'rememberMe')->checkbox() ?>

<div style="color:#999;margin:1em 0">
    Если вы забыли пароль, вы можете <?= Html::a('сбросить его', ['site/request-password-reset']) ?>.
</div>

<div class="form-group">
    <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
</div>

<?php ActiveForm::end(); ?>

