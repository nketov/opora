<?php

use common\components\ImagesIcons;
use common\components\TecDoc;
use common\models\Product;
use yii\data\ArrayDataProvider;
use yii\widgets\ListView;

$this->title = $model->name;


?>
    <div class="product-page">
        <div class="content-header"><?= $model->name ?></div>
        <div class="image_block">
            <div class="image_container">
                <?php if ($model->getFirstImage()) {
                    $src = $model->getFirstImage();
                    ?>
                    <img class="image" id="image" src="<?= $src ?>"/>
                <?php } else {
                    $src = '/images/main/logo.jpg';
                    ?>
                    <img class="image empty" src="<?= $src ?>"/>
                <?php } ?>
            </div>

            <div class="image_icons">
                <?= sizeof($model->getAllImages()) > 1 ? ImagesIcons::widget(['images' => $model->getAllImages()]) : '' ?>
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
                <?php if (!empty($model->article)) { ?>
                    <div class="string">
                        <div class="spec_name">Артикул:&nbsp;</div>
                        <div class="spec_value"><?= $model->article ?></div>
                    </div>
                <?php } ?>
                <?php if (!empty($model->category)) { ?>
                    <div class="string">
                        <div class="spec_name">Категория товара:&nbsp;</div>
                        <div class="spec_value"><?= $model->categoryName() ?></div>
                    </div>
                <?php } ?>
            </div>

            <div class="view_buttons">
                <div class="view_price"><?= $model->price > 0 ? number_format($model->getDiscountPrice(), 2, ',', '&nbsp;') . ' грн' : 'Цена не указана' ?></div>
                <?php if ($model->price > 0) { ?>
                    <p style="color: #00a65a">Есть в наличии</p>
                    <button
                            class="btn btn-primary btn-lg cd-add-to-cart"
                            data-id="<?= $model->id ?>"
                            data-price="<?= $model->getDiscountPrice() ?>"
                            data-name="<?= $model->name ?>"
                            data-image="<?= $src ?>">
                        ДОБАВИТЬ В КОРЗИНУ
                    </button>
                <?php } ?>
            </div>
        </div>
    </div>

<?php

if ($model->article && $analogs = TecDoc::getAnalogs($model->article)) {
    echo '<h1>Коммерческое предложение:</h1>';

    $dataProvider = new ArrayDataProvider([
        'allModels' => $analogs,
        'pagination' => [
            'pageSize' => false,
        ],
    ]);


    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'section',
            'class' => 'list-wrapper',
            'id' => 'category-list-wrapper',
        ],
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
            'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
            'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
            'maxButtonCount' => 6,
        ],

        'layout' => '<div class="cards-block">{items}</div>',
        'itemOptions' => ['class' => 'card'],
        'itemView' => '_card'
    ]);


} ?>