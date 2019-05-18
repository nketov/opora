<?php

use common\components\TecDoc;
use kartik\select2\Select2;
use yii\helpers\StringHelper;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Product;
use dosamigos\tinymce\TinyMce;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\widgets\ListView;

?>



<?php

for ($i = date('Y'); $i >= 1950; $i--) {
    $data[$i] = $i;
}

$form = ActiveForm::begin(['id' => 'tecdoc-search-form']); ?>


<?= $form->field($tecdocSearch, 'year')->widget(Select2::className(), [
    'data' => $data,
    'options' => [
        'id' => 'td_year',
        'role' => "button-cursor",
        'placeholder' => 'Выберите год',
    ],
    'pluginOptions' => [
        'allowClear' => true
    ],
    'theme' => Select2::THEME_BOOTSTRAP
])->label(false); ?>



<?= $form->field($tecdocSearch, 'mfa_id')->widget(DepDrop::classname(), [
    'options' => [
        'tabindex' => 1,
        'role' => 'button-cursor',
        'id' => 'td_mfa_id'
    ],
    'type' => DepDrop::TYPE_SELECT2,
    'select2Options' => [
        'theme' => Select2::THEME_BOOTSTRAP
    ],
    'pluginOptions' => [
        'depends' => ['td_year'],
        'url' => Url::to(['/tecdoc/mfa-drop-down?mfa_id=' . $tecdocSearch->mfa_id]),
        'loadingText' => 'Загрузка ...',
        'placeholder' => 'Выберите марку',
        'allowClear' => true,
        'initialize' => true,
    ],
])->label(false);
?>

<?= $form->field($tecdocSearch, 'mod_id')->widget(DepDrop::classname(), [
    'options' => [
        'tabindex' => 1,
        'role' => 'button-cursor',
        'id' => 'td_mod_id'
    ],
    'type' => DepDrop::TYPE_SELECT2,
    'select2Options' => [
        'theme' => Select2::THEME_BOOTSTRAP
    ],
    'pluginOptions' => [
        'depends' => ['td_mfa_id', 'td_year'],
        'url' => Url::to(['/tecdoc/model-drop-down?mod_id=' . $tecdocSearch->mod_id]),
        'loadingText' => 'Загрузка ...',
        'placeholder' => 'Выберите модель',
//            'allowClear' => true,
//            'initialize' => true,
    ],
])->label(false);
?>


<?= $form->field($tecdocSearch, 'type_id')->widget(DepDrop::classname(), [
    'options' => [
        'tabindex' => 1,
        'role' => 'button-cursor',
        'id' => 'td_type_id'
    ],
    'type' => DepDrop::TYPE_SELECT2,
    'select2Options' => [
        'theme' => Select2::THEME_BOOTSTRAP
    ],
    'pluginOptions' => [
        'depends' => ['td_mod_id', 'td_year'],
        'url' => Url::to(['/tecdoc/type-drop-down?type_id=' . $tecdocSearch->type_id]),
        'loadingText' => 'Загрузка ...',
        'placeholder' => 'Выберите тип модели',
//            'allowClear' => true,
//            'initialize' => true,
    ],
])->label(false);
?>

<?= Html::submitButton('Выбрать авто', ['class' => 'btn btn-primary td_submit']) ?>

<?php ActiveForm::end(); ?>
<hr>
<hr>
<div class="car-info">
    <?php
    if (!empty($car = TecDoc::getCookieCar())) { ?>
        <h2><?= $car['car_text'] ?></h2>
        <?= TecDoc::getTypeInfo($car['mod_id'], $car['year'], $car['type_id']) ?>
    <?php } ?>
</div>

<hr>
<hr>
<div id="td-category-panel">
    <?= $this->render('cat-selector', compact('tecdocSearch')) ?>
</div>
<div id="tecdoc-content">

    <section id="td-category-content">
        <?php
        echo Html::img('/images/gear.gif', ['id' => 'td_wheel-preloader']);


        $dataProvider = $tecdocSearch->search();
        Pjax::begin(['id' => 'pjax_car_category', 'timeout' => false]);
        if ($tecdocSearch['category']) {

            echo $this->render('_sorters', compact('dataProvider'));

            $filters =  $this->render('_filters', ['searchModel'=> $tecdocSearch]);

            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'options' => [
                    'tag' => 'section',
                    'class' => 'list-wrapper',
                    'id' => 'category-list-wrapper',
                ],
                'pager' => [
                    'firstPageLabel' => 'Первая',
                    'lastPageLabel' => 'Последняя',
                    'prevPageLabel' => '<span class="glyphicon glyphicon-chevron-left"></span>',
                    'nextPageLabel' => '<span class="glyphicon glyphicon-chevron-right"></span>',
                    'maxButtonCount' => 6,
                ],

                'layout' => '<div class="filters-cards">'.$filters.'<div class="cards-block">{items}</div></div>{summary}{pager}',
                'itemOptions' => ['class' => 'card'],
                'itemView' => '_card'
            ]);
        }
        Pjax::end();
        ?>
    </section>
</div>



