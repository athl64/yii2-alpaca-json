<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 09.02.18
 * Time: 12:46
 */

namespace dvixi\alpaca\models;

use yii\db\ActiveRecord;
use Yii;

class JsonPagePart extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%json_page_part}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => \yii\behaviors\TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'json_page_id' => Yii::t('app', 'Page'),
            'lang' => Yii::t('app', 'Locale'),
            'attribute' => Yii::t('app', 'Page attribute'),
            'json' => Yii::t('app', 'Json'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getJsonPage()
    {
        return $this->hasOne(JsonPage::className(), ['id' => 'json_page_id']);
    }
}