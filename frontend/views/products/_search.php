<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\Tree_1C;
use yii\widgets\Pjax;

Pjax::begin(['id' => 'pjax_form']);
$tree=new Tree_1C();

?>


<nav class="left-catalog">
    <?php foreach ($tree->getCategories() as $category) { ?>
        <div style="padding: 2px;margin:2px">
            <?= Html::a($category->name,
                Url::toRoute(['/category/' . $category->code]),
                ['title' => $category->name])
            ?>


            <div class="sub-menu">
                <?php
                $subcats=[];
                if (!empty($subcats = $tree->getSubCategories($category->code)))
                    foreach ($subcats as $sub_cat) echo
                        '<div style="padding: 2px;margin:2px">'.Html::a($sub_cat->name,
                            Url::toRoute(['/category/' . $sub_cat->code]),
                            ['title' => $sub_cat->name]). '</div>' ?>
            </div>
        </div>

    <?php } ?>

</nav>
<?php Pjax::end();
?>