<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

$this->title = 'Содержание страниц'; ?>


<div class="box">
    <div class="box-body">
        <?php $form = ActiveForm::begin(['id' => 'pages-content-form']); ?>

        <?= $form->field($model, 'actions')->widget(TinyMce::className(), [
            'options' => ['rows' => 10],
            'language' => 'ru',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]) ?>

        <?= $form->field($model, 'delivery')->widget(TinyMce::className(), [
            'options' => ['rows' => 10],
            'language' => 'ru',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]) ?>
        <?= $form->field($model, 'payment')->widget(TinyMce::className(), [
            'options' => ['rows' => 10],
            'language' => 'ru',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]) ?>
        <?= $form->field($model, 'guarantee')->widget(TinyMce::className(), [
            'options' => ['rows' => 10],
            'language' => 'ru',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]) ?>

        <?= $form->field($model, 'convention')->widget(TinyMce::className(), [
            'options' => ['rows' => 10],
            'language' => 'ru',
            'clientOptions' => [
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ]
        ]) ?>
<!--        --><?//= $form->field($model, 'vacancies')->widget(TinyMce::className(), [
//            'options' => ['rows' => 10],
//            'language' => 'ru',
//            'clientOptions' => [
//                'plugins' => [
//                    "advlist autolink lists link charmap print preview anchor",
//                    "searchreplace visualblocks code fullscreen",
//                    "insertdatetime media table contextmenu paste"
//                ],
//                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
//            ]
//        ]) ?>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary btn-lg center-block']) ?>
        <?php ActiveForm::end(); ?>
    </div>


</div>



