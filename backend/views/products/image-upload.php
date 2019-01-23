<?php

use common\models\Product;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$background = '';
$productModel = Product::findOne($model->product);

$productName = $productModel->name . ' (' . $productModel->code . ')';

if ($image = $model->key) {
    $this->title = 'Смена изображения для товара: ' . $productName;
    $background = 'style="background-image: url(/images/products/' . $productModel->$image . '?rnd=' . time() . ')"';
} else {
    $this->title = 'Новое изображение для товара: ' . $productName;
    $model->key = 'new';
}
?>

<div class="box ">
    <div class="box-body">
        <?php $form = ActiveForm::begin([
            'action' => '/admin/products/update?id=' . $model->product,
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>

        <?= Html::submitButton('Сохранить фото', [
            'class' => 'btn btn-lg btn-primary',
            'style' => 'margin: 15px auto'
        ]) ?>

        <?php if ($model->key != 'new') echo Html::button('Удалить фото', [
            'id' => 'image-delete-button',
            'class' => 'btn btn-lg btn-danger',
            'style' => 'margin: 15px auto',
            'data-key' => $model->key,
            'data-product' => $model->product,
            'data-toggle' => 'modal',
            'data-target' => '#image-delete-modal'
        ]) ?>

        <div id="image-preview" <?= $background ?> >
            <label for="image-upload" id="image-label">Выберите фото</label>
            <input type="text" name="ImageUploadForm[key]" value="<?= $model->key ?>" hidden/>
            <input type="text" name="ImageUploadForm[product]" value="<?= $model->product ?>" hidden/>
            <input type="file" name="ImageUploadForm[image]" id="image-upload"/>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>




