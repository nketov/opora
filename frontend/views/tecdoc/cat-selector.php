<?php

use common\components\TecDoc;
use common\models\TecdocSearch;
use kartik\select2\Select2;

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
        'allowClear' => true
    ],
    'theme' => Select2::THEME_BOOTSTRAP
]);

