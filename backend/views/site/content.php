<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Содержание сайта'; ?>



    <div class="box" style="width: 552px">
        <div class="box-body">
            <?php $form = ActiveForm::begin(['id' => 'content-form']); ?>
<!--            --><?//= $form->field($model, 'address')->textInput() ?>

            <?= $form->field($model, 'phones_header')->textInput() ?>

            <?= $form->field($model, 'address')->textInput() ?>

<!--            --><?//= $form->field($model, 'phone_footer_icon')->dropDownList([
//                'voodafone.png' => 'Voodafone',
//                'kyivstar.png' => 'KyivStar',
//                'life.png' => 'Life'
//
//            ]); ?>

            <?= $form->field($model, 'title')->textarea() ?>

            <?= $form->field($model, 'description')->textarea() ?>

            <?= $form->field($model, 'keywords')->textarea() ?>

            <?= $form->field($model, 'icq')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary center-block']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>


