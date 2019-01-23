<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Actions */

$this->title = 'Изменение акции';

?>
<div class="box" style="width: 552px">
    <div class="box-body actions-form-panel">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>
