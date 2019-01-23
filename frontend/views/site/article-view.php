<?php

use common\components\ImagesIcons;
use common\models\Product;

$this->title = $model->header;
?>


<div class="content-header"><?= $model->header ?></div>
<div class="container-article-view">
    <img src="/images/articles/<?= $model->image_name ?>" alt="" style="width:100%;margin: 30px">
    <div class="article-view" style="margin: 30px">
        <?= $model->content ?>
    </div>
</div>
