<?php


use frontend\assets\VueAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Category;

VueAsset::register($this);

?>

<!---->

<div id="app" class="wrapper" v-cloak v-bind:class="{'is-previous': isPreviousSlide, 'first-load': isFirstLoad}">
    <div class="slide-wrapper"
         v-for="(slide, index) in slides"
         v-bind:class="{ active: index === currentSlide }"
         v-bind:style="{ 'z-index': (slides.length - index), 'background-image': 'url(' + slide.bgImg + ')' }">
        <div class="slide-inner">
            <div class="slide-bg-text">
                <p>{{ slide.headlineFirstLine }}</p>
                <p>{{ slide.headlineSecondLine }}</p>
            </div>
            <div class="slide-rect-filter">
                <div class="slide-rect" v-bind:style="{'border-image-source': 'url(' + slide.rectImg + ')'}"></div>
            </div>
            <div class="slide-content">
                <h1 class="slide-content-text"><p>{{ slide.headlineFirstLine }}</p>
                    <p>{{ slide.headlineSecondLine }}</p></h1>
                <a href="/cabinet" class="slide-content-cta">Войти</a></div>
            <h2 class="slide-side-text">
                <span>{{ slide.sublineFirstLine }} / </span><span>{{ slide.sublineSecondLine }}</span></h2></div>
    </div>
    <div class="controls-container">
        <button class="controls-button"
                v-for="(slide, index) in slides"
                v-bind:class="{ active: index === currentSlide }"
                v-on:click="updateSlide(index)">{{ slide.headlineFirstLine }} {{ slide.headlineSecondLine }}
        </button>
    </div>
    <div class="pagination-container">
            <span class="pagination-item"
                  v-for="(slide, index) in slides"
                  v-bind:class="{ active: index === currentSlide }"
                  v-on:click="updateSlide(index)"></span>
    </div>
</div>

<div class="main-footer-links">
    <?=Category::getMainLinks() ?>
</div>

