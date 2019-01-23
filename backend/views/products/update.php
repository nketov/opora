<?php
$this->title = 'Редактирование товара: ' . $model->name . ' (' . $model->code . ')';

?>

<div class="box ">
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
</div>

<div class="box ">
    <div class="box-body">
        <?= $this->render('_images', [
            'model' => $model,
        ]) ?>
    </div>
</div>

