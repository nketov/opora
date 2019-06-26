<?php

use common\components\TecDoc;
use common\models\ProductTextSearch;
use frontend\components\Tree_1C;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;
use yii\helpers\Url;
$searchModel = new ProductTextSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
$tree = new Tree_1C();
?>
    <div class="top-panel">

        <div class="top-category">
            <nav class="left-catalog">
                <?php foreach ($tree->getCategories() as $category) { ?>
                <div style="padding: 2px;margin:2px">
                    <?= Html::a($category->name,
                    Url::toRoute(['/category/' . $category->code]),
                ['title' => $category->name])
            ?>
                    <div class="sub-menu">
                        <?php
                        $subcats = [];
                        if (!empty($subcats = $tree->getSubCategories($category->code)))
                        foreach ($subcats

                        as $sub_cat) {
                        echo
                            '<div style="padding: 2px;margin:2px">' . Html::a($sub_cat->name,
                                Url::toRoute(['/category/' . $sub_cat->code]),
                                ['title' => $sub_cat->name]) ?>

                        <div class="sub-submenu">
                            <?php
                            $sub_sub_cats = [];
                            if (!empty($sub_sub_cats = $tree->getSubCategories($sub_cat->code)))
                                foreach ($sub_sub_cats as $sub_sub_cat) echo
                                    '<div style="padding: 2px;margin:2px">' . Html::a($sub_sub_cat->name,
                                        Url::toRoute(['/category/' . $sub_sub_cat->code]),
                                        ['title' => $sub_sub_cat->name]) . '</div>' ?>
                        </div>
                    </div>
                <?php } ?>
                </div>
        </div>
        <?php } ?>
        </nav>

        <h3>Категории</h3>
        <a href="#" class="menu-btn btn btn-category">
            <span></span>
        </a>


    </div>

<?php
$car_text = 'Выберите свой автомобиль';
$car_delete = '';
if (!empty($car = TecDoc::getCookieCar())) {
    $car_delete = Html::button("&#10060", ['title' => "Убрать автомобиль", 'class' => 'header-car-delete']);
}

echo "<h4 class='header-car'>" .Html::img('/images/icons/car_icon.png',
        ['class' => '']). '<span>' .Html::a($car['car_text'] ?? 'Выберите свой автомобиль', '/car') . $car_delete .
    "</span></h4>";


$vin_text =  !empty($car['vin']) ? "VIN-код: &nbsp;".$car['vin'] : "";

echo "<span class='header-vin'>".$vin_text."</span>";


Pjax::begin(['id' => 'header_pjax_form'
]);

$form = ActiveForm::begin([
    'method' => 'get',
    'action' => '/search',
    'id' => 'header-search-form',
    'options' => [
        'data-pjax' => 0
    ],
]);
?>
<?= $form->field($searchModel, 'text', [
    'addon' => [
        'append' => [
            'content' => Html::button('<i class="fa fa-search fa-fw"></i>', ['type' => 'submit', 'class' => 'btn btn-primary btn-search']),
            'asButton' => true
        ]
    ]
])->label(false)->textInput(['placeholder' => "Поиск по названию или коду"]);

ActiveForm::end();
Pjax::end();

echo '</div>';
//}
?>