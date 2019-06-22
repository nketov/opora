<?php

use frontend\components\NovaPoshta;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = 'Поиск :';
?>

<?php Pjax::begin(['id' => 'pjax_text_search', 'timeout' => false]);

echo $this->render('_sorters', compact('dataProvider'));

$filters =  $this->render('_filters', compact('searchModel'));
if($dataProvider->models)
echo ListView::widget([
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

    'layout' => '<div class="filters-cards">'.$filters.'<div class="cards-block">{items}</div></div>{summary}{pager}',
    'itemOptions' => ['class' => 'card'],
    'itemView' => '_card'
]) ?>

<?php Pjax::end(); ?>
<hr>
<hr>
<h2>Поиск по объявлениям:</h2>
<div class="post-index">

    <?php Pjax::begin(['id' => 'pjax_sell_search', 'timeout' => false]); ?>

    <h3>Продажа</h3>

    <?= GridView::widget([
        'dataProvider' => $sellProvider,
        'showHeader' => false,
        'tableOptions' =>
            ['class' => 'table table-striped'],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'image',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 32px'],
                'value' => function ($data) {
                    return $data->image_name ? Html::img('/images/posts/' . $data->image_name . '?rnd=' . time(),
                        ['style'=> 'width:30px; height:auto']) : '' ;
                }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:35%'],
                'value' => function ($data) {
                    return Html::a($data->title, '/post/' . $data->id, ['data-pjax'=>0]);
                }
            ],

            [
                'attribute' => 'city_id',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:15%; color: grey; text-align:center; font-style: italic; font-size: .77em'],
                'value' => function ($data) {
                    return $data->city_id  ? (new NovaPoshta())->getCityNameRu($data->city_id).',<br>'.(new NovaPoshta())->getAreaNameRu($data->region_id). '&nbsp;обл.' : '';
                }
            ],

            [
                'attribute' => 'time',
                'contentOptions' => ['style' => 'text-align:right'],
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->time);
                }
            ],
            [
                'attribute' => 'price',
                'contentOptions' => ['style' => 'text-align:right; color: green'],
                'value' => function ($data) {
                    return $data->price >0 ? Yii::$app->formatter->asDecimal($data->price).' грн.' : '';
                }
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
        'layout' => '{items}{pager}',
    ]); ?>
    <?php Pjax::end(); ?>

    <?php Pjax::begin(['id' => 'pjax_buy_search', 'timeout' => false]); ?>

    <h3>Покупка</h3>

    <?= GridView::widget([
        'dataProvider' => $buyProvider,
        'showHeader' => false,
        'tableOptions' =>
            ['class' => 'table table-striped'],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'image',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width: 32px'],
                'value' => function ($data) {
                    return $data->image_name ? Html::img('/images/posts/' . $data->image_name . '?rnd=' . time(),
                        ['style'=> 'width:30px; height:auto']) : '' ;
                }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:50%'],
                'value' => function ($data) {
                    return Html::a($data->title, '/post/' . $data->id,['data-pjax'=>0]);
                }
            ],

            [
                'attribute' => 'city_id',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:15%; color: grey; text-align:center; font-style: italic; font-size: .77em'],
                'value' => function ($data) {
                    return $data->city_id  ? (new NovaPoshta())->getCityNameRu($data->city_id).',<br>'.(new NovaPoshta())->getAreaNameRu($data->region_id). '&nbsp;обл.' : '';
                }
            ],



            [
                'attribute' => 'time',
                'contentOptions' => ['style' => 'text-align:right'],
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->time);
                }
            ],
            [
                'attribute' => 'price',
                'contentOptions' => ['style' => 'text-align:right'],
                'value' => function ($data) {
                    return $data->price >0 ? Yii::$app->formatter->asDecimal($data->price).' грн.' : '';
                }
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
        'layout' => '{items}{pager}',
    ]); ?>
    <?php Pjax::end(); ?>
</div>


