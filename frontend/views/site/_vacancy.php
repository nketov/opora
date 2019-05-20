<?php

use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="vacancy-preview">
    <h2><?= $model->header ?></h2>
    <div><?= StringHelper::truncate($model->content, 500, '',null, true).'<br>'. Html::a('<b>Читать дальше...</b>',  Url::to('/vacancy/'.$model->id),['class'=>'btn']) ?></div>
</div>

<hr>
