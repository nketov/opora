<?php
use common\components\TecDoc;
use yii\helpers\StringHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

?>

<div>
    <h3 style='color : #ff0084'><b><?=TecDoc::getModelName(553) ?>:</b></h3></br>
    </br>

    <?php

    $infoProvider = new ArrayDataProvider([
        'allModels' => TecDoc::getModelInfo(553),
        'pagination' => [
            'pageSize' => 10,
            'route' => '/tecdoc',
        ],
    ]);


    echo GridView::widget([
        'id' => 'model-info-table',
        'dataProvider' => $infoProvider,
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

            'MOD_ID',

            [
                'attribute' => 'MOD_CDS_TEXT',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],
            [
                'attribute' => 'MOD_PCON_START',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],
            [
                'attribute' => 'MOD_PCON_END',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],

//
//                ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);


    echo '<hr>';
    echo '<hr>';
    echo '<hr>';
    echo '<hr>';

    $modelsProvider = new ArrayDataProvider([
        'allModels' => TecDoc::getModels(),
        'pagination' => [
            'pageSize' => 10,
            'route' => '/tecdoc',
        ],
    ]);


    echo GridView::widget([
        'id' => 'models-table',
        'dataProvider' => $modelsProvider,
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


    echo '<hr>';
    echo '<hr>';
    echo '<hr>';
    echo '<hr>';


    $typesProvider = new ArrayDataProvider([
        'allModels' => TecDoc::getTypes(3908),
        'pagination' => [
            'pageSize' => 10,
            'route' => '/tecdoc',
        ],
    ]);


    echo GridView::widget([
        'id' => 'types-table',
        'dataProvider' => $typesProvider,
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

            'TYP_ID',

            [
                'attribute' => 'MFA_BRAND',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],


            [
                'attribute' => 'MOD_CDS_TEXT',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],

            [
                'attribute' => 'TYP_CDS_TEXT',
                'contentOptions' => ['style' => 'width:275px;max-width:300px'],
            ],

            'TYP_PCON_START',
            'TYP_PCON_END',
            'TYP_CCM',
            'TYP_KW_FROM',
            'TYP_KW_UPTO',
            'TYP_HP_FROM',
            'TYP_HP_UPTO',
            'TYP_CYLINDERS',
            'TYP_ENGINE_DES_TEXT',
            'TYP_FUEL_DES_TEXT',
            'TYP_BODY_DES_TEXT',
            'TYP_AXLE_DES_TEXT',
            'TYP_MAX_WEIGHT',



//
//                ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);





    ?>




</div>


