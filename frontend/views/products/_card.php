<?php

use yii\helpers\StringHelper;

?>

<div class="card-contur" data-id="<?= $model->id ?>"></div>

<div class="card-img-block">
    <?php if ($model->getFirstImage()) {
        $src = $model->getFirstImage();
        ?>
        <img class="card-img" src="<?= $src ?>"/>
    <?php } else {
        $src = '/images/main/logo.jpg';
        ?>
        <img class="card-img empty" src="<?= $src ?>"/>
    <?php } ?>
</div>

<div class="card-text">
    <span class="card-text-text"><?= StringHelper::truncate($model->name, 42) ?></span>
    <span class="card-price"><?= $model->price > 0 ? number_format($model->getDiscountPrice(), 2, ',', '&nbsp;') . ' грн' : 'Цена не указана' ?></span>
</div>
<div class="info_hover">
    <?php if ($model->price > 0 && $model->remains > 0 ) { ?>
        <button
                class="btn btn-primary cd-add-to-cart"
                data-id="<?= $model->id ?>"
                data-price="<?= $model->getDiscountPrice() ?>"
                data-name="<?= $model->name ?>"
                data-remains="<?= $model->remains ?>"
                data-unit="<?= $model->unit ?>"
                data-image="<?= $src ?>"
        >ДОБАВИТЬ В КОРЗИНУ
        </button>
    <?php } ?>

    <?php if ($model->article) { ?>
    <p style="font-size: .7rem; font-weight: lighter">Артикул: <?= $model->article ?></p>
    <?php } ?>
</div>
