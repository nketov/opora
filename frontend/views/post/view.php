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

    <div class="content-header"><?= Html::encode($this->title) ?></div>
    <hr>
    <div class="well">
        <?= $model->text ?>
    </div>
    <hr>
    <h4 class="pull-left">
        <?= '&nbsp;&nbsp;<span class="glyphicon glyphicon-phone"></span>&nbsp;&nbsp;<b>' . $model->user->getPhone() . '</b>' ?>
    </h4>

    <div class="pull-right small">
        <?= Yii::$app->formatter->asDatetime($model->time).'&nbsp;&nbsp;' ?>
    </div>
    <br>
    <br>
    <br>
    <?php if ($model->user_id == Yii::$app->getUser()->id) { ?>
        <p class="pull-right">
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

</div>
