<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $searchModel common\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Объявления';

?>
<div class="box">
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--        <p>-->
<!--            --><?//= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
<!--        </p>-->

        <?= GridView::widget([
            'id' => 'post-table',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}{summary}{pager}",
            'pager' => [
                'hideOnSinglePage' => true,
                'prevPageLabel' => 'Педыдущая',
                'nextPageLabel' => 'Следующая',

            ],
            'tableOptions' => ['class' => 'table table-striped table-hover table-responsive '],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

//                ['attribute' => 'id',
//                    'contentOptions' => ['style' => 'width:50px;text-align:center'],
//                    'filter' => false,
//                ],
                [
                    'attribute' => 'title',
                    'contentOptions' => ['style' => 'width:350px;'],
                ],
//                'text:ntext',
                [
                    'attribute' => 'user.email',
                    'contentOptions' => ['style' => 'width:350px;text-align:center'],
                ],
                ['attribute' => 'time',
                    'filter' => false,
                    'contentOptions' => ['style' => 'width:200px;text-align:right'],
                    'value' => function ($data) {
                        return Yii::$app->formatter->asDatetime($data->time);
                    }
                ],
                //'type',
                //'image_name',
                //'price',
                //'article',
                //'new',
                ['attribute' => 'status',
                    'format' => 'raw',
                    'filter' => Post::getStatuses(),
                    'contentOptions' => ['class'=>'td-status','style' => 'width:130px;text-align:center'],
                    'value' => function ($data) {
                        return Html::dropDownList('status', $data->status, Post::getStatuses());
                    }
                ],

//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
