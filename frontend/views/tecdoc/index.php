<?php
use common\components\TecDoc;
use kartik\select2\Select2;
use yii\helpers\StringHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

?>

<div>
    <h3 style='color : #ff0084'><b>Все марки:</b></h3></br>
    </br>

    <?php

    $data['0'] = 'Не указан';
    for ($i=1950;$i<= date('Y'); $i++){
        $data[$i] = $i;
    }

    echo '<label class="control-label">Год выпуска:</label>';

    echo Select2::widget([
        'name' => 'year',
        'data' => $data,
        'pluginOptions' => [
            'width' => '150px'
        ],
    ]);

    echo '<hr>';

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


