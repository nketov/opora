<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\User;
use common\models\Product;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(['id' => 'actions-form']); ?>

    <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
        'id' => 'actions-user-select',
        'name' =>'actions-user',
        'data' => User::getUsersEmailsArray(),
        'theme' => Select2::THEME_CLASSIC,
        'options' => ['placeholder' => 'Выберите клиента',],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'product_id')->widget(Select2::classname(), [
        'id' => 'actions-product-select',
        'name' =>'actions-product',
        'data' => Product::getActiveCodesArray(),
        'theme' => Select2::THEME_CLASSIC,
        'options' => ['placeholder' => 'Вcе товары',],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]) ?>

    <?= $form->field($model, 'percent')->textInput([
    'style'=>'text-align:right;width:92px;margin:0 auto;font: bold 1rem Tahoma;'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>


