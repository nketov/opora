<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = 'Создание пары синонимов';
?>
<div class="box" style="width: 552px">
    <div class="box-body">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
