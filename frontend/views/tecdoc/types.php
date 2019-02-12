<?php
use common\components\TecDoc;
use yii\grid\GridView;
?>

<div>
    <h3 style='color : #ff0084'><b><?=TecDoc::getModelFullName($mod_id) ?>:</b></h3></br>

    <?php

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

//                ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);

    ?>

</div>


