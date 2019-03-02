<?php

use common\models\ProductTextSearch;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$searchModel = new ProductTextSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

if (Yii::$app->controller->action->id == 'text-search') {
    Pjax::begin(['id' => 'header_pjax_form']);

$form = ActiveForm::begin([
    'method' => 'get',
    'action' => '/search',
    'id' => 'header-search-form',
    'options' => [
        'data-pjax' => 1
    ],
]);

$fieldOptions = [
    'inputTemplate' => '{input}<i class="fa fa-search fa-fw form-control-feedback"></i>
      '
];


?>
<?= $form->field($searchModel, 'text', $fieldOptions)->label(false)->textInput(['placeholder' => "Поиск по названию или коду"]) ?>

<?php ActiveForm::end();

    Pjax::end();
}
?>