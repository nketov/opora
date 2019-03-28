<?php use yii\bootstrap\Button;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Product;

$form = ActiveForm::begin(['id' => 'excel-upload', 'options' => ['enctype' => 'multipart/form-data']]);


echo '<div class="box" style="width: 662px"><div class="box-body uploader-panel">';

$this->title = 'Загрузка товаров для магазинов из файла';

echo $form->field($model, 'shop')->dropDownList([
    '' => 'Выберите магазин',
//    '0' => Product::shopName(0),
    '1' => Product::shopName(1),
//    '2' => Product::shopName(2),
//    '3' => Product::shopName(3),
//    '4' => Product::shopName(4),
]);

echo $form->field($model, 'excelFile')->fileInput();

//echo $form->field($model, 'markup')->textInput([
//    'style'=>'text-align:right;width:92px;margin:0 auto; font: bold 1rem Tahoma;'
//]);

echo Button::widget([
    'label' => 'Загрузить данные из файла',
    'options' => ['class' => 'btn-primary'],
]);


echo '</div></div>';


//echo '<div class="box" style="width: 662px"><div class="box-body uploader-panel">';
//echo '<b>Шаблоны загружаемых файлов ".xls, .xlsx":</b></br>';
//
//foreach (Product::linksPatternList() as $key=>$link)
//echo Html::a(Product::shopName($key), $link).'</br>';
//
//echo '</div></div>';




ActiveForm::end() ?>