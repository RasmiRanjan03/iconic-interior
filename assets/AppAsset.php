<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Public site assets. Tailwind (CDN), Alpine.js and Google Fonts are loaded
 * in the layout <head>; this bundle carries the brand design system + interactions.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/iconic.css',
    ];
    public $js = [
        'js/iconic.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
