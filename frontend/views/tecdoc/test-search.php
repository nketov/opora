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

<div>
    <!--    <h3 style='color : #115522'><b>Поиск по текдоку:</b></h3></br>-->
    </br>

    <?php

    for ($i = 1950; $i <= date('Y'); $i++) {
        $data[$i] = $i;
    }

    $form = ActiveForm::begin(['id' => 'tecdoc-search-form']); ?>


    <?= $form->field($tecdocSearch, 'year')->widget(Select2::className(), [
        'data' => $data,
        'options' => [
            'id' => 'td_year',
            'role' => "button-cursor",
            'placeholder' => 'Выберите год ...',
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


    <?php

    Pjax::begin(['id' => 'car_pjax']);

    if ($tree) {
        $level_1_data = ArrayHelper::map($tree, 'STR_ID', 'STR_DES_TEXT');

        echo "<hr><div id='tree-levels-selectors'>";
        echo Select2::widget([
            'name' => 'level_1',
            'id' => 'tree_level_1',
            'data' => $level_1_data,
            'theme' => Select2::THEME_BOOTSTRAP,
            'pluginOptions' => [
                'placeholder' => 'Выберите раздел',
                'allowClear' => true
            ],

//            'disabled' => true
        ]);
        echo DepDrop::widget([
            'name' => 'level2',
            'options' => ['id' => 'tree_level_2'],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'theme' => Select2::THEME_BOOTSTRAP
            ],
            'pluginOptions' => [
                'depends' => ['tree_level_1'],
                'placeholder' => 'Выберите категорию',
                'loadingText' => 'Загрузка ...',
                'url' => Url::to(['/tecdoc/level2-drop-down']),
                'allowClear' => true
            ]
        ]);
        echo DepDrop::widget([
            'name' => 'level3',
            'options' => ['id' => 'tree_level_3'],
            'type' => DepDrop::TYPE_SELECT2,
            'select2Options' => [
                'theme' => Select2::THEME_BOOTSTRAP
            ],
            'pluginOptions' => [
                'depends' => ['tree_level_2'],
                'placeholder' => 'Выберите подкатегорию',
                'loadingText' => 'Загрузка ...',
                'url' => Url::to(['/tecdoc/level3-drop-down']),
                'allowClear' => true
            ]
        ]);
        echo "</div>";


        Pjax::begin(['id' => 'pjax_car_category']);


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

            'layout' => '<div class="cards-block">{items}</div>{summary}{pager}',
            'itemOptions' => ['class' => 'card'],
            'itemView' => '_card'
        ]);
        Pjax::end();
    }

    ?>

    <? Pjax::end(); ?>
</div>

<!---->
<?php // var_dump(TecDoc::getCategory()); ?>
<?php //// echo TecDoc::getLookup(3418057); ?>
<?php // var_dump( TecDoc::getLookup(1132344)); ?>


