<?php
use common\components\TecDoc;
use yii\helpers\StringHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

?>

<div>
    <h3 style='color : #ff0084' data-year="<?= $year ?>"><b><?=TecDoc::getBrandName($mfa_id) ?>:</b></h3></br>
    </br>

    <?php



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

    ?>




</div>


