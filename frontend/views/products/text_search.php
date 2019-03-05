<?php

use common\models\Product;
use yii\widgets\ListView;
use yii\widgets\Pjax;
$this->title = 'Поиск :';
?>
<!---->
<!--<div class="content-header">--><?//= $this->title ?><!--</div>-->

<?php Pjax::begin(['id' => 'pjax_text_search']); ?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
        'tag' => 'section',
        'class' => 'list-wrapper',
        'id' => 'category-list-wrapper',
    ],
    'pager' => [
        'firstPageLabel' => 'Первая',
        'lastPageLabel' => 'Последняя',
        'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
        'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
        'maxButtonCount' => 6,
    ],

    'layout' => '<div class="cards-block">{items}</div>{summary}{pager}',
    'itemOptions' => ['class' => 'card'],
    'itemView' => '_card'
]) ?>
<?php Pjax::end(); ?>


