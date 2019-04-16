<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="post-view">

    <?php if ($model->user_id == Yii::$app->getUser()->id) { ?>
        <p class="pull-right" style="z-index: 100">
            <?= Html::a('Редактировать объявление', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Удалить объявление', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Удалить объявление?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>

    <div class="content-header"><?= Html::encode($this->title) ?></div>


    <?php if ($model->image_name)
        echo Html::img('/images/posts/' . $model->image_name, ['class' => 'post-img center-block']);
    ?>

    <br>

    <div class="post-content">
        <div class="well">
            <?= $model->text ?>
        </div>

        <div class="view_spec">
            <div class="string">
                <div class="spec_name">Состояние:&nbsp;</div>
                <div class="spec_value"><?= $model::getNews()[$model->new] ?></div>
            </div>
            <?php if ($model->category > 0) { ?>
                <div class="string">
                    <div class="spec_name">Категория:&nbsp;</div>
                    <div class="spec_value"><?= $model::categoryName($model->category) ?></div>
                </div>
            <?php } ?>
            <?php if (!empty($model->article)) { ?>
                <div class="string">
                    <div class="spec_name">Артикул:&nbsp;</div>
                    <div class="spec_value"><?= $model->article ?></div>
                </div>
            <?php } ?>
            <?php if (!empty($model->region_id)) { ?>
                <div class="string">
                    <div class="spec_name">Область:&nbsp;</div>
                    <div class="spec_value"><?= $model->NP->getAreaNameRu($model->region_id) ?></div>
                </div>
            <?php } ?>

            <?php if (!empty($model->city_id)) { ?>
                <div class="string">
                    <div class="spec_name">Город:&nbsp;</div>
                    <div class="spec_value"><?= $model->NP->getCityNameRu($model->city_id) ?></div>
                </div>
            <?php } ?>
            <?php if ($model->price > 0) { ?>
                <div class="string">
                    <div class="spec_name">Цена:&nbsp;</div>
                    <div class="spec_value"><?= Yii::$app->formatter->asDecimal($model->price) . '&nbsp;грн.' ?></div>
                </div>
            <?php } ?>
        </div>
    </div>

    <hr>
    <h4 class="pull-left">
        <?= '&nbsp;&nbsp;<span class="glyphicon glyphicon-phone"></span>&nbsp;&nbsp;<b>' . $model->user->getPhone() . '</b>' ?>
    </h4>

    <div class="pull-right small">
        <?= Yii::$app->formatter->asDatetime($model->time) . '&nbsp;&nbsp;' ?>
    </div>


</div>
