<?php

use common\components\ImagesIcons;
use common\models\Product;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Товары';
?>
<div class="box">
    <div class="box-body">

        <!--    <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <!--    <p>-->
        <!--        --><? //= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
        <!--    </p>-->

        <?= GridView::widget([
            'id' => 'products-table',
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

//                'id',
                [
                    'attribute' => 'name',
                    'contentOptions' => ['style' => 'width:275px;max-width:300px'],
                    'value' => function ($data) {

                        return StringHelper::truncate($data->name, 36);
                    }
                ],

                [
                    'attribute' => 'code',
                    'contentOptions' => ['style' => 'width:225px;text-align:center'],
                ],

                [
                    'attribute' => 'article',
                    'contentOptions' => ['style' => 'width:225px;text-align:center'],
                ],

                [
                    'attribute' => 'category',
                    'contentOptions' => ['style' => 'width:225px;text-align:center'],
                ],

                ['attribute' => 'price',
                    'contentOptions' => ['style' => 'width:125px;text-align:right'],
                    'filter' => false,
                    'value' => function ($data) {
                        $currency = $data->currency == 1 ? 'грн' : 'валюта';
                        return $data->price . ' ' . $currency;
                    }

                ],

                [
                    'attribute' => 'remains',
                    'filter' => false,
                    'contentOptions' => ['style' => 'width:150px;max-width:300px;text-align:right'],
                    'value' => function ($data) {
                        return $data->remains . ' ' . $data->unit;
                    }
                ],

//
//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>


