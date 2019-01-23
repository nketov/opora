<?php

use common\models\Order;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';

?>

    <div class="box">
        <div class="box-body">

            <!--    <h1>--><? //= Html::encode($this->title) ?><!--</h1>-->
            <?php Pjax::begin(); ?>
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <p>
                <!--        --><? //= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
            </p>

            <?= GridView::widget([
                'id' => 'orders-table',
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

                    ['attribute' => 'id',
                        'contentOptions' => ['style' => 'min-width:15%;white-space: normal; text-align:center'],
                        'filter' => false
                    ],
                    [
                        'attribute' => 'date',
                        'contentOptions' => ['style' => 'min-width:200px;text-align:center'],
                        'value' => function ($data) {
                            return  date_create($data->date)->format(' d.m.Y H:i');
                        },
                        'filter' => false,
                    ],

                    ['attribute' => 'user_id',
                        'headerOptions' => ['style' => 'min-width:80px, text-align:right'],
                        'value' => function ($data) {
                            return $data->user->email ?? "Без регистрации";
                        },
                    ],
                    ['attribute' => 'order_content',
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'min-width:275px;max-width:500px'],
                    ],

                    ['attribute' => 'summ',
                        'contentOptions' => ['style' => 'min-width:125px;max-width:150px;text-align:right'],
                        'filter' => false,
                    ],

                    ['attribute' => 'status',
                        'format' => 'raw',
                        'filter' => Order::getStatuses(),
                        'contentOptions' => ['class'=>'td-status','style' => 'width:130px;text-align:center'],
                        'value' => function ($data) {
                            return Html::dropDownList('status', $data->status, Order::getStatuses());
                        }
                    ],

                ],
            ]); ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
<?php
$script = <<< JS
$('select[name="status"]').on('change',
    function() {
       var status =$(this).val();
       var order = $(this).closest('tr').data('key');
        $.ajax({
            method: "POST",
            url: '/admin/order/status',
            data: {status:status,order:order} 
          })
          .done(function( data ) {
           console.log('Status Changed');   
          });      
    }
);
JS;
$this->registerJs($script, yii\web\View::POS_READY);
?>