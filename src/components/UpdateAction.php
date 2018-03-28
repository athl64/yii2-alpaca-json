<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 03.03.18
 * Time: 14:27
 */

namespace dvixi\alpaca\components;

use yii\base\Action;
use dvixi\alpaca\helpers\JsonPageModuleHelper;
use yii\web\Controller;
use Yii;

class UpdateAction extends Action
{
    /**
     * @param null|string $lang
     * @return mixed
     */
    public function run($lang = null)
    {
        $lang = empty($lang)
            ? JsonPageModuleHelper::m()->defaultLanguage
            : $lang;

        $class = $this->controller->getModelClass();
        $model = $class::getRecord($lang);

        if (!$model) {
            $model = new $class;
            $model->lang = $lang;
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            // todo: add validation displaying for extremal cases
            $model->save();
        }

        $viewPath = JsonPageModuleHelper::m()->viewPath;

        return $this->controller->render( $viewPath . '/json-page/_form', [
            'model' => $model,
        ]);
    }
}