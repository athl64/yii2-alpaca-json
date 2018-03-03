<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 03.03.18
 * Time: 12:52
 */

namespace dvixi\alpaca\models;

use yii\db\ActiveRecord;
use yii\db\ActiveQuery;
use yii\helpers\Json;
use Yii;

class FrontJsonPage extends JsonPage
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->class_name = $this->formName();
    }

    /**
     * @param $lang string|null
     * @return ActiveRecord|static
     */
    public static function getRecord($lang = null)
    {
        if (!$lang) {
            $lang = Yii::$app->language;
        }

        $model = static::find()
            ->where([
                'class_name' => (new static)->formName(),
            ])
            ->with([
                'jsonPageParts' => function(ActiveQuery $query) use ($lang) {
                    return $query->andOnCondition(['lang' => $lang]);
                }
            ])
            ->one();
        if ($model) {
            // lang required for proper loading relation for partial json
            $model->lang = $lang;
        }

        return $model;
    }

    /**
     * Must be overrided in extended models
     * @return array
     */
    public function getJsonAttributes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $attributes = $this->getJsonAttributes();
        $partModels = $this->jsonPageParts;
        foreach ($attributes as $attribute) {
            foreach ($partModels as $partModel) {
                if ($partModel->attribute == $attribute) {
                    $phpData = Json::decode($partModel->json);
                    $this->$attribute = $phpData;
                    break;
                }
            }
        }
    }
}