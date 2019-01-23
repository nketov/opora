<?php
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use kartik\slider\Slider;
use common\models\Product;

Pjax::begin(['id' => 'pjax_form']);
$form = ActiveForm::begin([
    'method' => 'get',
    'action' => $formTarget,
    'id' => 'left-filter-form',
    'options' => [
        'data-pjax' => 1
    ],
]) ?>

<h3 style="font-weight: bold">Подбор по параметрам:</h3>

<?= $form->field($searchModel, 'shop')->label('Производитель')->dropDownList(Product::shopNamesList(),['prompt' => 'Любой']) ?>

<div class="form-group field-productsearch-prices">
    <label class="control-label">Цена</label><br>
    <?php
    echo Slider::widget([
            'id' => 'prices',
            'name' => 'ProductSearch[prices]',
            'value' => $searchModel->prices[0] . ',' . $searchModel->prices[1],
            'sliderColor' => Slider::TYPE_PRIMARY,
            'pluginOptions' => [
                'min' => $searchModel->minPrice,
                'max' => $searchModel->maxPrice,
                'step' => 1,
                'range' => true
            ],
            'pluginEvents' => [
                "slideStop" => "function() { $(this).closest('form').submit(); }",
            ]
        ]) . '<div class="slider-text">oт <b class="badge bage-min">' . $searchModel->prices[0] . ' грн</b> до <b class="badge bage-max">' . $searchModel->prices[1] . ' грн</b></div>';
    ?>
</div>

<?= $form->field($searchModel, 'withoutPricesShow')->checkbox(); ?>

<?= $form->field($searchModel, 'color')->dropDownList(Product::colorsNamesList(),['prompt' => 'Любой']) ?>

<?= $form->field($searchModel, 'material')->dropDownList(Product::materialsNamesList(),['prompt' => 'Любой']) ?>

<?= $form->field($searchModel, 'withoutImageShow')->checkbox(); ?>

<?php ActiveForm::end();
Pjax::end();
?>