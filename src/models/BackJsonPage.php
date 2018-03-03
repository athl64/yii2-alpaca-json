<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 03.03.18
 * Time: 12:41
 */

namespace dvixi\alpaca\models;

use dvixi\alpaca\helpers\JsonPageModuleHelper;
use yii\base\InvalidConfigException;
use Yii;

abstract class BackJsonPage extends JsonPage
{
    /**
     * @return string
     */
    abstract public function getTitle():string;

    /**
     * Key is  the model attribute, value is the json config for Alpaca
     * @return array
     */
    abstract public function getJsonConfig():array;

    /**
     * @return array
     */
    abstract public function getJsonOptions():array;

    /**
     * Return array of model attributes that used for storing JSON parts
     * @return array
     */
    abstract public function getJsonAttributes():array;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->class_name = $this->formName();
        if (!$this->lang) {
            $this->lang = JsonPageModuleHelper::m()->defaultLanguage;
        }
        $this->loadDefaultJsonData();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'label' => Yii::t('app', 'Page label (for backend)'),
            'published' => Yii::t('app', 'Published'),
            'class_name' => Yii::t('app', 'Class Name'),
        ];
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
     * @return array
     */
    public function getJsonPartModels()
    {
        $result = [];
        $jsonConfig = $this->getJsonConfig();
        foreach ($jsonConfig as $attribute => $item) {
            $sectionModel = new JsonPagePart();
            $result[$attribute] = $sectionModel;
        }

        return $result;
    }

    /**
     * @param $lang string
     * @return mixed
     */
    public static function getRecord($lang)
    {
        $model = static::find()
            ->where([
                'class_name' => (new static)->formName(),
            ])
            ->with([
                'jsonPageParts' => function(\yii\db\ActiveQuery $query) use ($lang) {
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
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        $this->savePageModel();
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $attributes = array_keys($this->getJsonConfig());
        $partModels = $this->jsonPageParts;
        foreach ($attributes as $attribute) {
            foreach ($partModels as $partModel) {
                if ($partModel->attribute == $attribute) {
                    $this->$attribute = $partModel->json;
                    break;
                }
            }
        }
    }

    /**
     * @return  void
     */
    public function savePageModel()
    {
        $attributes = array_keys($this->getJsonConfig());
        $partModels = $this->jsonPageParts;
        foreach ($attributes as $attribute) {
            $model = null;
            foreach ($partModels as $partModel) {
                if ($partModel->attribute == $attribute) {
                    $model = $partModel;
                }
            }
            if (!$model) {
                $model = new JsonPagePart();
                $model->lang = $this->lang;
                $model->json_page_id = $this->id;
                $model->attribute = $attribute;
            }
            $jsonValue = empty($this->$attribute)
                ? null
                : $this->$attribute;
            $model->json = $jsonValue;

            $model->save(false);
        }
    }

    /**
     * @return void
     */
    public function loadDefaultJsonData()
    {
        $data = $this->getJsonDefaultData();
        foreach ($data as $attribute => $datum) {
            if ($this->hasProperty($attribute)) {
                $this->$attribute = $datum;
            }
        }
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getJsonDefaultData():array
    {
        $attributes = $this->getJsonAttributes();
        $result = [];
        foreach ($attributes as $item) {
            $reflection = new \ReflectionClass($this);
            $directory = dirname($reflection->getFileName());
            $jsonPath = $directory . '/json/' . $this->formName() . '/' . $item . '.json';
            $jsonContent = file_exists($jsonPath)
                ? file_get_contents($jsonPath)
                : null;
            $result[$item] = $jsonContent;
        }

        return $result;
    }
}