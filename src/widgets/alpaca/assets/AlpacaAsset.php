<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.18
 * Time: 14:19
 */

namespace dvixi\alpaca\widgets\alpaca\assets;

use yii\web\AssetBundle;

class AlpacaAsset extends AssetBundle
{
    public $sourcePath = '@dvixi/alpaca/widgets/alpaca/assets';
    public $css = [
        'css/alpaca.min.css',
        'css/yii2-alpaca.css'
    ];
    public $js = [
        'js/handlebars.min.js',
        'js/alpaca.min.js',
        'js/yii2-alpaca.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}