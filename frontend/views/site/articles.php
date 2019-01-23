<?php

use yii\widgets\ListView;


$this->title = 'Статьи';

?>

<div class="container-articles">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'options' => [
            'tag' => 'section',
            'class' => 'list-wrapper',
            'id' => 'articles-list-wrapper',
        ],
        'pager' => [
            'firstPageLabel' => 'Первая',
            'lastPageLabel' => 'Последняя',
            'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
            'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
            'maxButtonCount' => 6,
        ],

        'layout' => '<div class="articles-block">{items}</div>{summary}{pager}',
        'itemOptions' => ['class' => 'article'],
        'itemView' => '_article'
    ]) ?>
</div>


