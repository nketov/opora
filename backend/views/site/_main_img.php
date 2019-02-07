<?php

use yii\helpers\Html;

if (!empty($product)) {
    if (!empty($img = $product->getFirstImage())) {
        echo '<img  data-product="' . $product->id . '" class="image_icon" src="/images/products/' . $product->getFirstImage() . '?rnd=' . time() . '"/>';
    } else {
        echo '<img  data-product="' . $product->id . '" class="image_icon" src="/images/main/logo.jpg"/>';
    }
    echo Html::button($product->code, ['class' => 'btn btn-success btn-block btn-flat']);
} else {
    echo '<img  class="image_icon" src="/admin/images/background.jpg" style="opacity: .25"/>';
    echo Html::button('Добавить товар', ['class' => 'btn btn-primary btn-block btn-flat']);
} ?>


