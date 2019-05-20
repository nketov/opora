<?php

use yii\helpers\Html;




$this->title = 'Создание вакансии';

?>




<div class="box" style="width: 700px">

    <div class="box-body">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>
</div>