<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Вопросы';

?>

<div class="container-contact">
    <div>
        <h3>
            <center>Если у вас есть деловые вопросы или другие вопросы,<br> пожалуйста, заполните следующую форму,<br> чтобы
                связаться с нами. <br>Спасибо.
            </center>
        </h3>


        <?php $form = ActiveForm::begin(['enableClientValidation' => true, 'id' => 'contact-form']); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+38 (099) 999 99 99',
            'clientOptions' => [
                'removeMaskOnSubmit' => true,
            ]
        ])->textInput();
        ?>

        <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


    <div>
        <h2> Контакты:</h2>

        <p><img src="/images/icons/email.png" alt=""> mail@opora.dn.ua</p>
        <p><img src="/images/icons/icq.png" alt=""> <?= $content->icq ?></p>
        <p><img src="/images/phone32.png" alt=""> <br><?= str_replace(',','<br>',$content->phones_header) ?></p>



</div>

