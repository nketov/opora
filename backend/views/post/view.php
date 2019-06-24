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
                    'attribute' => 'image_1',
                    'format' => 'raw',
                    'label' => 'Изображение 1',
                    'value' => function ($data) {
                        return $data->image_1 ? Html::img('/images/posts/' . $data->image_1,['style'=> 'max-width:70vw']) : "";
                    }
                ],
                [
                    'attribute' => 'image_2',
                    'format' => 'raw',
                    'label' => 'Изображение 2',
                    'value' => function ($data) {
                        return $data->image_2 ? Html::img('/images/posts/' . $data->image_2,['style'=> 'max-width:70vw']) : "";
                    }
                ],
                [
                    'attribute' => 'image_3',
                    'format' => 'raw',
                    'label' => 'Изображение 3',
                    'value' => function ($data) {
                        return $data->image_3 ? Html::img('/images/posts/' . $data->image_3,['style'=> 'max-width:70vw']) : "";
                    }
                ],
                [
                    'attribute' => 'image_4',
                    'format' => 'raw',
                    'label' => 'Изображение 4',
                    'value' => function ($data) {
                        return $data->image_4 ? Html::img('/images/posts/' . $data->image_4,['style'=> 'max-width:70vw']) : "";
                    }
                ],
                [
                    'attribute' => 'image_5',
                    'format' => 'raw',
                    'label' => 'Изображение 5',
                    'value' => function ($data) {
                        return $data->image_5 ? Html::img('/images/posts/' . $data->image_5,['style'=> 'max-width:70vw']) : "";
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
