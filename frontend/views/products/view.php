<?php

use common\components\ImagesIcons;
use common\models\Product;

$this->title = $model->name;
?>
<div class="content-header"><?= $model->name ?></div>
<div class="image_block">
    <div class="image_container">
        <?php if ($model->images_count > 0) {
            $src='/images/products/'.$model->getFirstImage();
            ?>
            <img class="image" id="image" src="<?= $src ?>"/>
        <?php } else {
            $src='/images/main/logo.png';
            ?>
            <img class="image empty" src="<?= $src ?>"/>
        <?php } ?>
    </div>

    <div class="image_icons">
        <?= $model->images_count > 1 ? ImagesIcons::widget(['images' => $model->getImages()]) : '' ?>
    </div>

</div>

<div class="view_info">
    <?php if (!empty($model->description)) { ?>
        <div class="view_description">
            <h4 style="font-weight: bold;text-align: center">Описание</h4>
            <div><?= $model->description ?></div>
        </div>
    <?php } ?>

    <div class="view_spec">

        <div class="string">
            <div class="spec_name">Артикул:&nbsp;</div>
            <div class="spec_value"><?= $model->code ?></div>
        </div>

        <div class="string">
            <div class="spec_name">Категория товара:&nbsp;</div>
            <div class="spec_value"><?= Product::categoryName($model->category) ?></div>
        </div>

        <?php if (!empty($model->material)) { ?>
            <div class="string">
                <div class="spec_name">Материал:&nbsp;</div>
                <div class="spec_value"><?= Product::materialName($model->material) ?></div>
            </div>
        <?php } ?>

        <?php if (!empty($model->color)) { ?>
            <div class="string">
                <div class="spec_name">Цвет:&nbsp;</div>
                <div class="spec_value"><?= Product::colorName($model->color) ?></div>
            </div>
        <?php } ?>

        <?php if (!empty($model->lamps)) { ?>
            <div class="string">
                <div class="spec_name">Лампочки:&nbsp;</div>
                <div class="spec_value"><?= $model->lamps ?></div>
            </div>
        <?php } ?>

        <?php if (!empty($model->height)) { ?>
            <div class="string">
                <div class="spec_name">Высота:&nbsp;</div>
                <div class="spec_value"><?= $model->height ?>&nbsp;мм</div>
            </div>
        <?php } ?>

        <?php if (!empty($model->diametr)) { ?>
            <div class="string">
                <div class="spec_name">Диаметр:&nbsp;</div>
                <div class="spec_value"><?= $model->diametr ?>&nbsp;мм</div>
            </div>
        <?php } ?>

        <?php if (!empty($model->width)) { ?>
            <div class="string">
                <div class="spec_name">Ширина:&nbsp;</div>
                <div class="spec_value"><?= $model->width ?>&nbsp;мм</div>
            </div>
        <?php } ?>

        <?php if (!empty($model->depth)) { ?>
            <div class="string">
                <div class="spec_name">Глубина:&nbsp;</div>
                <div class="spec_value"><?= $model->depth ?>&nbsp;мм</div>
            </div>
        <?php } ?>

        <?php if (!empty($model->size)) { ?>
            <div class="string">
                <div class="spec_name">Размеры:&nbsp;</div>
                <div class="spec_value"><?= $model->size ?></div>
            </div>
        <?php } ?>
    </div>


    <div class="view_buttons">
        <div class="view_price"><?= $model->price > 0 ? number_format($model->getDiscountPrice(), 2, ',', '&nbsp;') . ' грн' : 'Цена не указана' ?></div>
        <p style="color: #00a65a">Есть в наличии</p>
        <button
                class="btn btn-primary btn-lg cd-add-to-cart"
                data-id="<?= $model->id ?>"
                data-price="<?= $model->getDiscountPrice() ?>"
                data-name="<?= $model->name ?>"
                data-image="<?= $src ?>">
            ДОБАВИТЬ В КОРЗИНУ
        </button>
    </div>
</div>
