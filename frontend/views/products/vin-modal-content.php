<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;



$form = ActiveForm::begin([
        'action' => '/cabinet',
    'enableClientValidation' => true,
]); ?>
    <div class="modal-header" style="text-align: center">
        <h4 class="modal-title">Укажите VIN-код для автомобиля: <br> <h3><?=$user_car->car_name ?></h3></h4>
    </div>
    <div class="modal-body" style="font-weight:  bold">
        <?= $form->field($user_car, 'vin')->textInput([
            'VIN-код' => 'Фамилия Имя Отчество',
            'style' => 'width:95%;margin: 0 auto; text-align:center'
        ])->label(false) ?>
        <?= $form->field($user_car, 'position')->hiddenInput()->label(false) ?>
    </div>
    <div class="modal-footer" style="text-align: center">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
    </div>
<?php ActiveForm::end(); ?>