<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = 'Вход';

$fieldOptions1 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-envelope form-control-feedback'></span>"
];

$fieldOptions2 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-lock form-control-feedback'></span>"
];

$fieldOptions3 = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}<span class='glyphicon glyphicon-phone form-control-feedback'></span>"
];
?>

<!--LOGIN-FORM-->

<section class="container-login">
    <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => [
        'class' => 'login-form'
    ]]); ?>

    <?= $form
        ->field($model, 'email', $fieldOptions1)
        ->label(false)
        ->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>

    <?= $form
        ->field($model, 'password', $fieldOptions2)
        ->label(false)
        ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

    <div class="row">
        <div class="col-xs-7">
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-block btn-flat btn-lg', 'name' => 'login-button']) ?>
        </div>
        <!-- /.col -->
    </div>

    <div class="form-footer-text toggle">
        Если Вы новый пользователь, то можете <?= Html::a('зарегистрироваться', ['signup']) ?>.
    </div>

    <div class="form-footer-text reset" style="">
        Если Вы забыли пароль, можете его <?= Html::a('восстановить') ?>.
    </div>
    <?php ActiveForm::end(); ?>


    <!--SignUp-FORM-->

    <?php $form = ActiveForm::begin(['id' => 'signup-form',
        'enableClientValidation' => true,
        'options' => [
            'class' => 'login-form'
        ]]); ?>

    <?= $form
        ->field($signupModel, 'email', $fieldOptions1)
        ->label(false)
        ->textInput(['placeholder' => $signupModel->getAttributeLabel('email'), 'autofocus' => true]) ?>

    <?= $form
        ->field($signupModel, 'password', $fieldOptions2)
        ->label(false)
        ->passwordInput(['placeholder' => $signupModel->getAttributeLabel('password')]) ?>


    <?= $form->field($signupModel, 'phone', $fieldOptions3)->widget(MaskedInput::className(), [
        'mask' => '+38 (099) 999 99 99',
        'clientOptions' => [
            'removeMaskOnSubmit' => true,
        ]
    ])->label(false)
        ->textInput(['placeholder' => $signupModel->getAttributeLabel('phone'), 'autofocus' => true]) ?>

    <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-block btn-flat btn-lg', 'name' => 'login-button']) ?>

    <div class="form-footer-text toggle">
        Если Вы уже зарегистрированы, можете <?= Html::a('войти', ['/']) ?>.
    </div>

    <div class="form-footer-text reset" style="">
        Если Вы забыли пароль, можете его <?= Html::a('восстановить') ?>.
    </div>
    <?php ActiveForm::end(); ?>


    <!--REQUEST-PASSWORD_RESET-FORM-->

    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'options' => [
        'class' => 'login-form'
    ]]); ?>
    <p>Пожалуйста, укажите свой E-mail, на него будет выслана ссылка на восстановление
        пароля.</p>

    <?= $form
        ->field($passwordModel, 'email', $fieldOptions1)
        ->label(false)
        ->textInput(['placeholder' => $passwordModel->getAttributeLabel('email'), 'autofocus' => true]) ?>

    <?= Html::submitButton('Выслать ссылку', ['class' => 'btn btn-primary btn-block btn-flat btn-lg', 'name' => 'login-button']) ?>

    <?php ActiveForm::end(); ?>


</section>






