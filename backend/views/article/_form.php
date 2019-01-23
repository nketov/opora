<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'header')->textInput() ?>

<?= $form->field($model, 'content')->widget(TinyMce::className(), [
    'options' => ['rows' => 16],
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

<?= $form->field($model, 'image')->fileInput() ?>

    <img src="<?= '/images/articles/' . $model->image_name.'?rnd=' . time() ?>" alt="" style="height:77px;width: 75px">

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary center-block']) ?>
    </div>

<?php ActiveForm::end(); ?>