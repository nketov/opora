<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = 'Редактирование объявления: <br>' . $model->title;
?>
<div class="post-update">

    <div class="content-header" ><?= $this->title ?></div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
