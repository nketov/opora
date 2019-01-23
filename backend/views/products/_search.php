<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'shop') ?>

    <?= $form->field($model, 'active') ?>

    <?= $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'image_1') ?>

    <?php // echo $form->field($model, 'image_2') ?>

    <?php // echo $form->field($model, 'image_3') ?>

    <?php // echo $form->field($model, 'image_4') ?>

    <?php // echo $form->field($model, 'image_5') ?>

    <?php // echo $form->field($model, 'color') ?>

    <?php // echo $form->field($model, 'material') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'diametr') ?>

    <?php // echo $form->field($model, 'width') ?>

    <?php // echo $form->field($model, 'depth') ?>

    <?php // echo $form->field($model, 'lamps') ?>

    <?php // echo $form->field($model, 'second_code') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
