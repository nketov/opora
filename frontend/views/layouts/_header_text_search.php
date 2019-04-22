<?php

use common\models\ProductTextSearch;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\form\ActiveForm;

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

//$fieldOptions = [
//    'inputTemplate' => '{input}<i class="fa fa-search fa-fw form-control-feedback"></i>'
//];
?>
<?= $form->field($searchModel, 'text', [
        'addon' => [
            'append' => [
                'content' => Html::button('<i class="fa fa-search fa-fw"></i>', ['type'=>'submit','class'=>'btn btn-primary btn-search']),
                'asButton' => true
            ]
        ]
    ])->label(false)->textInput(['placeholder' => "Поиск по названию или коду"]) ?>

<?php ActiveForm::end();

    Pjax::end();
}
?>