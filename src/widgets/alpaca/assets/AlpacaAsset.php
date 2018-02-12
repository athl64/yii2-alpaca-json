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
    public $sourcePath = '@backend/modules/jsonPage/widgets/alpaca/assets';
    public $css = [
        'css/alpaca.min.css',
    ];
    public $js = [
        'js/handlebars.min.js',
        'js/alpaca.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}