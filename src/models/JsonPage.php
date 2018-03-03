<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.18
 * Time: 14:52
 */

namespace dvixi\alpaca\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\AciveQuery;

class JsonPage extends ActiveRecord
{
    /**
     * @var string
     */
    public $lang;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%json_page}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getJsonPageParts()
    {
        return $this->hasMany(JsonPagePart::class, ['json_page_id' => 'id']);
    }
}