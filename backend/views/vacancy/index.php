<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Вакансии';

?>
<div class="box">

    <div class="box-body">

        <p>
            <?= Html::a('Создать вакансию', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'id' => 'vacancies-table',
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
