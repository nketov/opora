<?php

use yii\helpers\StringHelper;

?>

<div class="card-contur"></div>

<?php if ($model->getFirstImage()) {
    $src=$model->getFirstImage();
    ?>
    <img class="card-img" src="<?=$src ?>"/>
<?php } else {
    $src='/images/main/logo.jpg';
    ?>
    <img class="card-img empty" src="<?=$src ?>"/>
<?php } ?>

<div class="card-text">
    <span class="card-text-text"><?= StringHelper::truncate($model->name, 42) ?></span>
    <span class="card-price"><?= $model->price >0 ?  number_format($model->getDiscountPrice(), 2, ',', '&nbsp;') .' грн' : 'Цена не указана' ?></span>
</div>
<div class="info_hover">

    <button
            class="btn btn-primary cd-add-to-cart"
            data-id="<?= $model->id ?>"
            data-price="<?= $model->getDiscountPrice() ?>"
            data-name="<?= $model->name ?>"
            data-image="<?= $src ?>"
    >ДОБАВИТЬ В КОРЗИНУ</button>


    <p style="font-size: .7rem; font-weight: lighter">Артикул: <?= $model->article ?></p>
</div>
