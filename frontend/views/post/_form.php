<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use frontend\components\NovaPoshta;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $form yii\widgets\ActiveForm */
if ($model->user_id != Yii::$app->getUser()->id) {
    return '';
}
?>


<div class="post-form">

    <?php $form = ActiveForm::begin();

    for ($i = 1; $i <= 5; $i++) {
        $_name='image_' . $i;
        echo $form->field($model, $_name )->label('<h2>' . Html::tag('span', '', ['class' => 'glyphicon glyphicon-picture']) . '&nbsp;Выберите изображение&nbsp;№' . $i . '</h2>')->fileInput();

        if ($model->$_name) { ?>
            <img class='post-img' src="<?= '/images/posts/' . $model->$_name.'?rnd=' . time() ?>" alt="">
        <?php }
    }
    ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(TinyMce::className(), [
        'options' => ['rows' => 20],
        'language' => 'ru',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste image"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        ]
    ]) ?>

    <?= $form->field($model, 'type')->dropDownList($model::getTypes()) ?>

    <?= $form->field($model, 'new')->dropDownList($model::getNews()) ?>

    <?= $form->field($model, 'category')->dropDownList($model::categoryNamesList()) ?>

    <?= $form->field($model, 'article')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_id')->widget(Select2::className(), [
        'data' => $model->NP->getAreasListRu(),
        'options' => [
            'id' => 'post_region',
            'role' => "button-cursor",
            'placeholder' => 'Выберите область',
        ],
        'theme' => Select2::THEME_BOOTSTRAP
    ]) ?>

    <?= $form->field($model, 'city_id')->widget(DepDrop::classname(), [
        'options' => [
            'tabindex' => 1,
            'role' => 'button-cursor',
            'id' => 'post_city'
        ],
        'type' => DepDrop::TYPE_SELECT2,
        'select2Options' => [
            'theme' => Select2::THEME_BOOTSTRAP
        ],
        'pluginOptions' => [
            'depends' => ['post_region'],
            'url' => Url::to(['/post/np-city-drop-down?city_id=' . $model->city_id]),
            'loadingText' => 'Загрузка ...',
            'placeholder' => 'Выберите город',
            'allowClear' => true,
            'initialize' => true,
        ],

    ]);
    ?>

    <?= $form->field($model, 'price')->textInput(['inputOptions' => ['value' => Yii::$app->formatter->asDecimal($model->price)],
        'maxlength' => true]) ?>


    <br>
    <div>
        <?= 'Мой телефон: <b>' . Html::a($model->user->getPhone(), '/cabinet') . '</b>' ?>
    </div>


    <div class="form-group center-block">
        <?= Html::submitButton('Cохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?><!---->

</div>
