<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\Tree_1C;

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


<!---->
<!---->
<!--        <div id="app" class="wrapper" v-cloak v-bind:class="{'is-previous': isPreviousSlide, 'first-load': isFirstLoad}">-->
<!--            <div class="slide-wrapper"-->
<!--                 v-for="(slide, index) in slides"-->
<!--                 v-bind:class="{ active: index === currentSlide }"-->
<!--                 v-bind:style="{ 'z-index': (slides.length - index), 'background-image': 'url(' + slide.bgImg + ')' }">-->
<!--                <div class="slide-inner">-->
<!--                    <div class="slide-bg-text">-->
<!--                        <p>{{ slide.headlineFirstLine }}</p>-->
<!--                        <p>{{ slide.headlineSecondLine }}</p>-->
<!--                    </div>-->
<!--                    <div class="slide-rect-filter">-->
<!--                        <div class="slide-rect" v-bind:style="{'border-image-source': 'url(' + slide.rectImg + ')'}"></div>-->
<!--                    </div>-->
<!--                    <div class="slide-content">-->
<!--                        <h1 class="slide-content-text"><p>{{ slide.headlineFirstLine }}</p><p>{{ slide.headlineSecondLine }}</p></h1><a href="/" class="slide-content-cta">Войти</a></div>-->
<!--                    <h2 class="slide-side-text"><span>{{ slide.sublineFirstLine }} / </span><span>{{ slide.sublineSecondLine }}</span></h2></div>-->
<!--            </div>-->
<!--            <div class="controls-container">-->
<!--                <button class="controls-button"-->
<!--                        v-for="(slide, index) in slides"-->
<!--                        v-bind:class="{ active: index === currentSlide }"-->
<!--                        v-on:click="updateSlide(index)">{{ slide.headlineFirstLine }} {{ slide.headlineSecondLine }}</button>-->
<!--            </div>-->
<!--            <div class="pagination-container">-->
<!--            <span class="pagination-item"-->
<!--                  v-for="(slide, index) in slides"-->
<!--                  v-bind:class="{ active: index === currentSlide }"-->
<!--                  v-on:click="updateSlide(index)"></span>-->
<!--            </div>-->
<!--        </div>-->


