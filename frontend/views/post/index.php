<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Доска объявлений';
Yii::$app->formatter->locale = 'ru-RU'
?>

<div class="post-index">
    <div class="content-header" ><?= Html::encode($this->title) ?></div>
    <br>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'showHeader' => false,
        'tableOptions' =>
            ['class' => 'table table-striped'],
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'contentOptions' => ['style' => 'width:70%'],
                'value' => function ($data) {
                    return Html::a($data->title, '/post/' . $data->id);
                }
            ],

            [
                'attribute' => 'time',
                'contentOptions' => ['style' => 'text-align:right'],
                'value' => function ($data) {
                    return Yii::$app->formatter->asDate($data->time);
                }
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
        'layout' => '{items}{pager}',
    ]); ?>
    <?php Pjax::end(); ?>
    <p>
        <?= Html::a('Разместить объявление', ['create'], ['class' => 'btn btn-success pull-right']) ?>
    </p>

</div>

