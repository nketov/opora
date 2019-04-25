<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\Tree_1C;

//echo Yii::$app->formatter->asDatetime(time());
//var_dump($np->getArea('','71508129-9b87-11de-822f-000c2965ae0e'));
//var_dump($np->getCities());

//var_dump($np->getAreasListRu());
//

//var_dump($np->getCityNameRu('db5c8927-391c-11dd-90d9-001a92567626'));
//var_dump($np->getWarehouseNameRu('db5c8927-391c-11dd-90d9-001a92567626','2bb8cec2-e1c2-11e3-8c4a-0050568002cf'));
//var_dump($np->getCitiesByArea($np->getAreas()['data'][4]['Ref']));
//
//
//
//foreach ($np->getWarehouses('db5c8927-391c-11dd-90d9-001a92567626')['data'] as $warehouse){
//    var_dump($warehouse);
//};
$tree = new Tree_1C();

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
                $subcats = [];
                if (!empty($subcats = $tree->getSubCategories($category->code)))
                foreach ($subcats

                as $sub_cat) {
                echo
                    '<div style="padding: 2px;margin:2px">' . Html::a($sub_cat->name,
                        Url::toRoute(['/category/' . $sub_cat->code]),
                        ['title' => $sub_cat->name]) ?>

                <div class="sub-submenu">
                    <?php
                    $sub_sub_cats = [];
                    if (!empty($sub_sub_cats = $tree->getSubCategories($sub_cat->code)))
                        foreach ($sub_sub_cats as $sub_sub_cat) echo
                            '<div style="padding: 2px;margin:2px">' . Html::a($sub_sub_cat->name,
                                Url::toRoute(['/category/' . $sub_sub_cat->code]),
                                ['title' => $sub_sub_cat->name]) . '</div>' ?>
                </div>
            </div>
        <?php } ?>
        </div>
        </div>
    <?php } ?>
</nav>


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

