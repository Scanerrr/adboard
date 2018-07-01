<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Вышеупомянутая ошибка возникла, когда веб-сервер обрабатывал ваш запрос.
    </p>
    <p>
        Если вы считаете, что это ошибка сервера, свяжитесь с нами. спасибо.
    </p>

</div>
