<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 09.02.18
 * Time: 16:26
 */

namespace dvixi\alpaca\widgets\elfinder;


use yii\helpers\Html;
use mihaildev\elfinder\AssetsCallBack;
use yii\helpers\Json;

class InputFile extends \mihaildev\elfinder\InputFile
{
    public function run()
    {
        AssetsCallBack::register($this->getView());
    }

    /**
     * @return mixed
     */
    public function getManagerOptions()
    {
        return $this->_managerOptions;
    }
}