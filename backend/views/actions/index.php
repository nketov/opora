<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ActionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Акции';

?>
<div class="box">
    <div class="box-body">
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a('Создать акцию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
        <?= GridView::widget([
            'id' => 'actions-table',
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
//                    'contentOptions' => ['style' => 'min-width:15%;white-space: normal; text-align:center'],
//                    'filter' => false
//                ],

                ['attribute' => 'user_id',
                    'contentOptions' => ['style' => 'min-width:80px; text-align:left'],
                    'value' => function ($data) {
                        return $data->user->email;
                    },
                ],

                ['attribute' => 'product_id',
                    'contentOptions' => ['style' => 'min-width:275px;max-width:500px; text-align:center'],
                    'value' => function ($data) {
                        return $data->product->code ?? 'Все товары';
                    },
                ],


                ['attribute' => 'percent',
                    'contentOptions' => ['style' => 'width:100px;text-align:center'],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'contentOptions' => ['style' => 'width:35px;text-align:center'],

                ],
            ],

        ]); ?>
    <?php Pjax::end(); ?>
</div>
</div>
