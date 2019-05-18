<?php use yii\widgets\ActiveForm;
use common\models\Product;
use kartik\select2\Select2;

?>
<aside class="filters">
    <?php $form = ActiveForm::begin([
        'method' => 'post',
        'options' => [
            'data-pjax' => 1
        ],
    ]) ?>

    <?= $form->field($searchModel, 'brands')->checkboxList($searchModel->brandsList)
//        ->label('Производители: ');
        ->label(false);
    ?>
    <?php ActiveForm::end(); ?>
</aside>