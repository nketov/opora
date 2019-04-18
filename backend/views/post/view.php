<?php

use common\models\Post;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = 'Объявление: "' . $model->title . '"';
\yii\web\YiiAsset::register($this);
?>
<div class="box">
    <div class="box-body">

        <?= DetailView::widget([
            'id' => 'post-view',
            'model' => $model,
            'attributes' => [
                ['attribute' => 'status',
                    'format' => 'raw',
                    'contentOptions' => ['data-key' => $model->id,'class' => 'td-status', 'style' => 'width:130px;text-align:center'],
                    'value' => function ($data) {
                        return Html::dropDownList('status', $data->status, Post::getStatuses());
                    }
                ],
                'id',
//                'title',
                [
                    'attribute' => 'text',
                    'format' => 'raw'
                ],
                [
                'attribute' => 'category',
                    'value' => function ($data) {
                        return Post::categoryName($data->category);
                    }
                ],
                'user.email',
                [
                    'attribute' => 'time',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asDatetime($data->time);
                    }
                ],
                [
                    'attribute' => 'type',
                    'value' => function ($data) {
                        return Post::getTypes()[$data->type];
                    }
                ],
                [
                    'attribute' => 'image_name',
                    'format' => 'raw',
                    'label' => 'Изображение',
                    'value' => function ($data) {
                        return $data->image_name ? Html::img('/images/posts/' . $data->image_name,['style'=> 'max-width:70vw']) : "";
                    }
                ],
                'price',
                'article',
                [
                    'attribute' => 'new',
                    'value' => function ($data) {
                        return Post::getNews()[$data->new];
                    }
                ],

            ],
        ]) ?>

        <p>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger pull-right',
                'data' => [
                    'confirm' => 'Удалить объявление?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

    </div>
</div>
