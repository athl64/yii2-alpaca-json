<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.18
 * Time: 14:49
 */

namespace dvixi\alpaca\controllers;


use dvixi\alpaca\helpers\JsonPageModuleHelper;
use dvixi\alpaca\models\JsonPage;
use yii\web\Controller;
use Yii;

abstract class JsonPageController extends Controller
{
    /**
     * @return JsonPage|string
     */
    abstract function getClassName();

    public function actionUpdate($lang = null)
    {
        $lang = empty($lang)
            ? JsonPageModuleHelper::m()->defaultLanguage
            : $lang;

        $class = $this->getClassName();
        $model = $class::getRecord($lang);

        if (!$model) {
            $model = new $class;
            $model->lang = $lang;
        }

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->save();
        }

        return $this->render('@dvixi/alpaca/views/json-page/_form', [
            'model' => $model,
        ]);
    }
}