<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 03.03.18
 * Time: 14:25
 */

namespace dvixi\alpaca\components;


interface BackendControllerInterface
{
    /**
     * Return class of model
     * Requires for UpdateAction
     * @return string
     */
    public function getModelClass();
}