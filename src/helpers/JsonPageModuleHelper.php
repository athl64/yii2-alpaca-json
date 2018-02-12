<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 09.02.18
 * Time: 13:10
 */

namespace dvixi\alpaca\helpers;

use yii\base\InvalidConfigException;

class JsonPageModuleHelper
{
    /**
     * @var string
     */
    public static $moduleName = 'jsonPage';

    /***
     * @param null|string $name
     * @return null|\dvixi\alpaca\Module|\yii\base\Module
     * @throws InvalidConfigException
     */
    public static function m($name = null)
    {
        if (is_null($name)) {
            $name = static::$moduleName;
        }
        if (!\Yii::$app->hasModule($name)) {
            throw new InvalidConfigException('Wrong module name! You need call this method with right jsonPage module name.');
        }

        return \Yii::$app->getModule($name);
    }
}