<?php

use frontend\models\OrderForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;

?>

<div class="cd-cart-container empty">
    <a href="#0" class="cd-cart-trigger">
        <ul class="count">
            <li>0</li>
            <li>0</li> <!-- for animation -->
        </ul>
    </a>
    <div class="cd-cart">
        <div class="wrapper">
            <header>
                <h2><i class="fa fa-shopping-basket"></i> Корзина</h2>
                <span class="undo">Товар удалён. <a href="#0">Отменить</a></span>
            </header>

            <div class="body">
                <ul>

                </ul>
            </div>
            <footer>
                <a data-toggle="modal" data-target="#order-modal"
                   class="checkout btn-cart"><em>ЗАКАЗАТЬ &nbsp;&nbsp;&nbsp;<span>0</span>&nbsp;грн</em></a>
            </footer>
        </div>
    </div>
</div>


<div id="order-modal" class="modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
            $model = new OrderForm();
            if (!Yii::$app->user->isGuest) {
                $user=Yii::$app->user->getIdentity();
                $model->phone = $user->phone;
                $model->FIO = $user->FIO;
            }

            $form = ActiveForm::begin([
                'enableClientValidation' => true,
                'action' => '/products/order'
            ]); ?>
            <div class="modal-header" style="text-align: center">
                <h2 class="modal-title">Укажите данные для заказа</h2>
            </div>
            <div class="modal-body" style="font-weight: bold; padding: 25px">
                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '+38 (099) 999 99 99',
                    'clientOptions' => [
                        'removeMaskOnSubmit' => true,
                    ]
                ])->textInput(['style' => 'width:165px;']);
                ?>
                <?= $form->field($model, 'FIO')->textInput(['placeholder' => 'Фамилия Имя Отчество'])?>
                <?= $form->field($model, 'delivery')->dropDownList($model::deliveryNamesList());?>


                <div class="nova-poshta-block np-hide">
                    <?= $form->field($model, 'region_id')->widget(Select2::className(), [
                        'data' => $model->NP->getAreasListRu(),
                        'options' => [
                            'id' => 'np_region',
                            'role' => "button-cursor",
                            'placeholder' => 'Выберите область',
                        ],
                        'theme' => Select2::THEME_BOOTSTRAP
                    ])->label(false) ?>

                    <?= $form->field($model, 'city_id')->widget(DepDrop::classname(), [
                        'options' => [
                            'tabindex' => 1,
                            'role' => 'button-cursor',
                            'id' => 'np_city'
                        ],
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => [
                            'theme' => Select2::THEME_BOOTSTRAP
                        ],
                        'pluginOptions' => [
                            'depends' => ['np_region'],
                            'url' => Url::to(['/products/np-city-drop-down?city_id=' . $model->city_id]),
                            'loadingText' => 'Загрузка ...',
                            'placeholder' => 'Выберите город',
                            'allowClear' => true,
                            'initialize' => true,
                        ],

                    ])->label(false);
                    ?>

                    <?= $form->field($model, 'warehouse_id')->widget(DepDrop::classname(), [
                        'options' => [
                            'tabindex' => 1,
                            'role' => 'button-cursor',
                            'id' => 'np_warehouse'
                        ],
                        'type' => DepDrop::TYPE_SELECT2,
                        'select2Options' => [
                            'theme' => Select2::THEME_BOOTSTRAP
                        ],
                        'pluginOptions' => [
                            'depends' => ['np_city'],
                            'url' => Url::to(['/products/np-warehouse-drop-down?warehouse_id=' . $model->warehouse_id]),
                            'loadingText' => 'Загрузка ...',
                            'placeholder' => 'Выберите отделение',
                            'allowClear' => true,
                            'initialize' => true,
                        ],
                    ])->label(false);
                    ?>
                </div>
                <div class="nova-courier-address np-hide">
                    <?= $form->field($model, 'courier_address')->textInput(['placeholder' => 'Укажите адрес доставки'])->label(false)?>
                </div>

            </div>
            <div class="modal-footer" style="text-align: center">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>