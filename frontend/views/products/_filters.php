<?php use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;

?>
<aside class="filters">
    <?php $form = ActiveForm::begin([
        'method' => 'get',
        'options' => [
            'data-pjax' => true
        ],

    ]) ?>

    <?= $form->field($searchModel, 'brands')->checkboxList($searchModel->brandsList)
        ->label('<h4>Производители: </h4>');
//        ->label(false);
    ?>
    <?=Html::button('Очистить выбор',['class' => 'btn']) ?>
    <?php ActiveForm::end(); ?>
</aside>