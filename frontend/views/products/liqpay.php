<?php

use frontend\components\LiqPay;
$this->title = 'Оплата заказа #:'.$model->id;
$liqpay = new LiqPay();
$html = $liqpay->cnb_form(array(
    'action'         => 'pay',
    'amount'         => $model->summ,
    'currency'       => 'UAH',
    'description'    => 'Оплата заказа № '.$model->id,
    'order_id'       => $model->id,
    'version'        => '3',
));

?>
<div class="content-header"><?=$this->title ?></div>
<br>
<div class="page-content">
    <?=$model->order_content ?>
    <br>
    <br>
    <?=$html ?>
</div>

