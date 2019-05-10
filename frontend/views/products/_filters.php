<?php use yii\widgets\ActiveForm;
use common\models\Product;
use kartik\select2\Select2;

?>
<div class="filters">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]) ?>

    <?= $form->field($searchModel, 'brand')->widget(Select2::className(), [
        'data' => Product::brandsList(),
        'options' => [
            'id' => 'brand_search',
            'role' => "button-cursor",
            'placeholder' => 'Любой',
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP
    ])->label('Производитель') ?>
    <?php ActiveForm::end(); ?>
    <span> Сортировка:&nbsp;&nbsp;
        <?= $dataProvider->sort->link('price') ?>
        &nbsp;&nbsp;
        <?= $dataProvider->sort->link('remains') ?>
        &nbsp;&nbsp;
    </span>

</div>