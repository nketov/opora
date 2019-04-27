<?php

use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<img src="/images/articles/<?= $model->image_name ?>" alt="">
<div class="article-preview">
    <h2><?= $model->header ?></h2>
    <div><?= StringHelper::truncate($model->content, 500, '',null, true).'<br>'. Html::a('<b>Читать дальше...</b>',  Url::to('/article/'.$model->id)) ?></div>
</div>


