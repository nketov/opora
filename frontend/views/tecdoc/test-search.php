<?php

use common\components\TecDoc;
use kartik\select2\Select2;
use yii\helpers\StringHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Product;
use dosamigos\tinymce\TinyMce;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<div>
    <h3 style='color : #115522'><b>Поиск по текдоку:</b></h3></br>
    </br>

    <?php


    for ($i = 1950; $i <= date('Y'); $i++) {
        $data[$i] = $i;
    }

    $form = ActiveForm::begin(['id' => 'tecdoc-search-form']); ?>


    <?= $form->field($tecdocSearch, 'year')->widget(Select2::className(), [
        'data' => $data,
        'options' => [
            'id' => 'td_year',
            'role' => "button-cursor",
            'placeholder' => 'Выберите год ...'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP
    ]); ?>



    <?= $form->field($tecdocSearch, 'mfa_id')->widget(DepDrop::classname(), [
        'options' => [
            'tabindex' => 1,
            'role' => 'button-cursor',
            'id'=>'td_mfa_id'
        ],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => [
            'theme' => Select2::THEME_BOOTSTRAP
        ],
        'pluginOptions' => [
            'depends' => ['td_year'],
            'url' => Url::to(['/tecdoc/mfa-drop-down?mfa_id=' . $tecdocSearch->mfa_id]),
            'loadingText' => 'Загрузка ...',
            'placeholder' => 'Выберите марку',
            'allowClear' => true,
            'initialize' => true,
        ],
    ]);
    ?>

    <?= $form->field($tecdocSearch, 'mod_id')->widget(DepDrop::classname(), [
        'options' => [
            'tabindex' => 1,
            'role' => 'button-cursor',
            'id'=>'td_mod_id'
        ],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => [
            'theme' => Select2::THEME_BOOTSTRAP
        ],
        'pluginOptions' => [
            'depends' => ['td_mfa_id','td_year'],
            'url' => Url::to(['/tecdoc/model-drop-down?mod_id=' . $tecdocSearch->mod_id]),
            'loadingText' => 'Загрузка ...',
            'placeholder' => 'Выберите модель',
            'allowClear' => true,
            'initialize' => true,
        ],
    ]);
    ?>



    <!--        <div class="form-group" style="text-align: center">-->
    <!--            --><? //= Html::submitButton('Сохранить', ['class' => 'btn btn-lg btn-primary',]) ?>
    <!--        </div>-->


    <?php ActiveForm::end(); ?>


</div>


