<?php

use frontend\components\NovaPoshta;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доска объявлений';
Yii::$app->formatter->locale = 'ru-RU'
?>

<div class="content-header"><?= Html::encode($this->title) ?></div>
<div class="post-index">

    <?php Pjax::begin(); ?>

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
                    return $data->image_1 ? Html::img('/images/posts/' . $data->image_1 . '?rnd=' . time(),
                                    ['style'=> 'width:30px; height:auto']) : '' ;
                }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:35%'],
                'value' => function ($data) {
                    return Html::a($data->title, '/post/' . $data->id);
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

    <?php Pjax::begin(); ?>

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
                    return $data->image_1 ? Html::img('/images/posts/' . $data->image_1 . '?rnd=' . time(),
                        ['style'=> 'width:30px; height:auto']) : '' ;
                }
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:50%'],
                'value' => function ($data) {
                    return Html::a($data->title, '/post/' . $data->id);
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

<p>
    <?= Html::a('Разместить объявление', ['/post/create'], ['class' => 'btn btn-success btn-lg pull-right']) ?>
</p>
