<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Смена пароля';

$fieldOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

?>
<section class="container-login">


    <?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'options' => [
        'class' => 'login-form'
    ]]); ?>
    <p>Пожалуйста, выберите новый пароль:</p>
    <?= $form
        ->field($model, 'password', $fieldOptions)
        ->label(false)
        ->passwordInput(['placeholder' => 'Пароль']) ?>

    <?= Html::submitButton('Установить новый пароль', ['class' => 'btn btn-primary btn-block btn-flat btn-lg']) ?>

    <?php ActiveForm::end(); ?>

</section>

