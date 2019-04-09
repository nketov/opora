<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->user->email ?? 'Без регистрации'  . ' ' . Yii::$app->formatter->asDatetime($model->date);

?>
<div class="box">
    <div class="box-body">

        <?= DetailView::widget([
            'id' => 'order-view',
            'model' => $model,
            'attributes' => [
                ['attribute' => 'id',
                    'contentOptions' => ['style' => 'max-width:10%;white-space: normal;'],
                    'filter' => false
                ],
                ['attribute' => 'user_id',
                    'headerOptions' => ['style' => 'min-width:80px, text-align:right'],
                    'value' => function ($data) {
                        return $data->user->email ?? "Без регистрации";
                    },
                ],
                'order_content:raw',
                'summ',
                ['attribute' => 'status',
                    'headerOptions' => ['style' => 'min-width:80px, text-align:right'],
                    'value' => function ($data) {
                        return \common\models\Order::getStatuses()[$data->status];
                    },
                ],
            ],
        ]) ?>

    </div>
</div>
