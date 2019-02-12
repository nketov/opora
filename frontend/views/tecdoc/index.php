<?php
use common\components\TecDoc;
use yii\helpers\StringHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

?>

<div>
    <h3 style='color : #ff0084'><b>Все марки:</b></h3></br>
    </br>

    <?php

    echo GridView::widget([
        'id' => 'brands-table',
        'dataProvider' => $brandsProvider,
//            'filterModel' => $searchModel,
        'layout' => "{items}{summary}{pager}",
        'pager' => [
            'hideOnSinglePage' => true,
            'prevPageLabel' => 'Педыдущая',
            'nextPageLabel' => 'Следующая',

        ],
        'tableOptions' => ['class' => 'table table-striped table-hover table-responsive '],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'MFA_ID',

            [
                'attribute' => 'MFA_BRAND',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],

//
//                ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);





    ?>




</div>


