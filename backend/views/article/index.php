<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';

?>
<div class="box">

    <div class="box-body">

        <p>
            <?= Html::a('Написать статью', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'id' => 'articles-table',
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
//                ['class' => 'yii\grid\SerialColumn'],

//                ['attribute' => 'id',
//                    'contentOptions' => ['style' => 'min-width:15%;white-space: normal; text-align:center'],
//                    'filter' => false
//                ],

                ['attribute' => 'image',
                     'filter' => false,
                    'format' => 'html',
                    'contentOptions' => ['style' => 'min-width:75px;max-width:80px; text-align:center'],
                    'value' => function ($data) {
                        return '<img src="/images/articles/'. $data->image_name. '?rnd=' . time().'" alt="" style="height:75px;width: 75px">';                         ;

                    },
                ],

                ['attribute' => 'header',
                    'filter' => false,
                    'contentOptions' => ['style' => 'min-width:80px; text-align:left;'],
                    'value' => function ($data) {
                        return $data->header;
                    },
                ],



                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'contentOptions' => ['style' => 'width:35px;text-align:center'],

                ],
            ],

        ]); ?>
    </div>
</div>
