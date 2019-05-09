<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Заказ № ' . $model->id;
?>

<div class="content-header"><?=$this->title ?></div>
<br>
<div class="page-content">
    <?=$model->order_content ?>
</div>



