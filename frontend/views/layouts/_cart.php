<?php

use frontend\models\UnregisteredUser;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

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
                <a href="/products/order" data-guest="<?= (int)Yii::$app->user->isGuest ?>"
                   class="checkout btn-cart"><em>ЗАКАЗАТЬ &nbsp;&nbsp;&nbsp;<span>0</span>&nbsp;грн</em></a>
            </footer>
        </div>
    </div>
</div>


<div id="order-phone-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php
            $user = new UnregisteredUser();

            $form = ActiveForm::begin([
                'enableClientValidation' => true,
                'action' => '/products/order'
            ]); ?>
            <div class="modal-header" style="text-align: center">
                <h3 class="modal-title">Укажите номер телефона для заказа</h3>
            </div>
            <div class="modal-body" style="text-align: center; height: 70px;font-weight:  bold">
                <?php
                echo $form->field($user, 'phone')->label(false)->widget(\yii\widgets\MaskedInput::className(), [
                    'mask' => '+38 (099) 999 99 99',
                    'clientOptions' => [
                        'removeMaskOnSubmit' => true,
                    ]
                ])->textInput(['style' => 'width:165px;margin: 0 auto;']);
                ?>
            </div>
            <div class="modal-footer" style="text-align: center">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>