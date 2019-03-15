<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Синонимы для поиска';

?>
<div class="box">
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Создать пару синонимов', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?= GridView::widget([
            'id' => 'synonym-table',
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

                'first',
                'second',

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
