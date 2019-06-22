<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main page vue asset bundle.
 */
class VueMainPageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style.css'
    ];
    public $js = [
       'https://cdn.jsdelivr.net/npm/vue',
       'js/vue_main_page.js',
    ];


}
