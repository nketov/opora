<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
if ($model->user_id != Yii::$app->getUser()->id) {
    return '';
}
?>


<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(TinyMce::className(), [
        'options' => ['rows' => 20],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste image"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        ]
    ]) ?>

    <?= $form->field($model, 'type')->dropDownList($model::getTypes()) ?>

    <?= $form->field($model, 'new')->dropDownList($model::getNews()) ?>

    <?= $form->field($model, 'category')->dropDownList($model::categoryNamesList()) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->fileInput() ?>

    <?= $form->field($model, 'price')->textInput(['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->price)],
        'maxlength' => true]) ?>

    <?php if($model->image_name){ ?>
    <img class='post-img' src="<?= '/images/posts/' . $model->image_name . '?rnd=' . time() ?>" alt="">
    <?php } ?>
    <br>
    <div>
        <?= 'Мой телефон: <b>' . Html::a($model->user->getPhone(), '/cabinet') . '</b>' ?>
    </div>


    <div class="form-group pull-right">
        <?= Html::submitButton('Cохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?><!---->

</div>
