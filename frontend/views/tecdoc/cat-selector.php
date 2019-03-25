<?php

use common\components\TecDoc;
use common\models\TecdocSearch;
use kartik\depdrop\DepDrop;use kartik\select2\Select2;
use yii\helpers\Url;

$tree = [];
if ($tecdocSearch->type_id)
    $tree = TecDoc::getTreeArray($tecdocSearch->type_id)[0]['child'] ?? [];

$td_levels = [];
foreach ($tree as $level_1) {
    $level_2_array = [];
    if (isset($level_1['child']))
        foreach ($level_1['child'] as $level_2) {
            $level_2_array = [];
            foreach ($level_1['child'] as $level_2) {
                $level_2_array[$level_2['STR_ID']] = " " . $level_2['STR_DES_TEXT'];
            }
        }
    $td_levels[$level_1['STR_DES_TEXT']] = $level_2_array;
}

echo Select2::widget([
    'data' => $td_levels,
    'name' => 'td_category',
    'options' => [
        'id' => 'td_category',
        'role' => "button-cursor",
        'placeholder' => 'Выберите категорию',

    ],
    'pluginOptions' => [
        'allowClear' => false
    ],
    'theme' => Select2::THEME_BOOTSTRAP
]);

echo "<br>";

echo DepDrop::widget([
    'options' => [
        'tabindex' => 1,
        'role' => 'button-cursor',
        'id' => 'td_sub_cat'
    ],
    'name'=>'td_sub_cat',
    'type' => DepDrop::TYPE_SELECT2,
    'select2Options' => [
        'theme' => Select2::THEME_BOOTSTRAP
    ],
    'pluginOptions' => [
        'depends' => ['td_category'],
        'url' => Url::to(['/tecdoc/sub-cat-drop-down']),
        'loadingText' => 'Загрузка ...',
        'placeholder' => 'Выберите подкатегорию',
//            'allowClear' => true,
//            'initialize' => true,
    ],
]);
?>

