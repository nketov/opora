<?php
use common\models\Product;
use common\models\Category;
use frontend\components\Tree_1C;
use yii\widgets\ListView;
use yii\widgets\Pjax;



if (isset($searchModel->category)) {
    $category= Category::findOne($searchModel->category);
    $this->title = 'Категория: '.$category->name;
    $formTarget = '/category/' . (int)$category->code;
} else {
    $this->title = 'Все категории';
    $formTarget = '/catalog';
}

?>



<div class="content-header"><?= $this->title ?></div>
<!--<div class="category-page">-->
<!--<aside class="left">-->
<!--    --><?php //echo $this->render('_search', compact('searchModel')); ?>
<!--</aside>-->

<?php Pjax::begin(['id' => 'pjax_list','timeout' => false]);
$filters = $this->render('_filters', compact('dataProvider','searchModel'));
?>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => [
        'action' => $formTarget,
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

    'layout' => '<div class="cards-block">{items}</div>'.$filters.'{summary}{pager}',
    'itemOptions' => ['class' => 'card'],
    'itemView' => '_card'
]) ?>
<?php Pjax::end(); ?>
<!--</div>-->


