<?php

use common\components\ImagesIcons;

?>
<div id="images-form">
    <?= ImagesIcons::widget(['images' => $model->getImages(), 'product_edit' => $model->id]) ?>
</div>
