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

                ['attribute' => 'price',
                    'contentOptions' => ['style' => 'width:100px;text-align:right'],
                    'filter' => false,
                ],

                ['attribute' => 'shop',
                    'contentOptions' => ['style' => 'width:155px;text-align:center'],
                    'value' => function ($data) {
                        return Product::shopName($data->shop);
                    },
                    'filter' => Product::shopNamesList(),
                ],

                ['attribute' => 'category',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width:260px;text-align:center'],
                    'filter' => Product::categoryNamesList(),
                    'value' => function ($data) {
                        return Product::categoryName($data->category);
                    }],

//                'description:ntext',
                ['attribute' => 'images_count',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width:245px;text-align:center'],
                    'filter' => Product::categoryNamesList(),
                    'value' => function ($data) {
                        return ImagesIcons::widget(['images' => $data->getImages()]);
                    }],
//                'image_2',
//                'image_3',
//                'image_4',
//                'image_5',
//                'color',
//                'material',
                //'height',
                //'diametr',
                //'width',
                //'depth',
                //'lamps',
                //'second_code',

                ['attribute' => 'active',
                    'format' => 'raw',
                    'filter' => Product::getStatuses(),
                    'contentOptions' => ['class'=>'td-active','style' => 'width:130px;text-align:center'],
                    'value' => function ($data) {
                        return Html::dropDownList('active', $data->active, Product::getStatuses());
                    }
                ],
//                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>


