<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.02.18
 * Time: 16:33
 */

namespace dvixi\alpaca\gii;

use yii\helpers\Json;
use Yii;

class Generator extends \yii\gii\Generator
{
    public $moduleNamespace = 'dvixi\alpaca';
    public $class;
    public $jsonObjects;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['moduleNamespace', 'class'], 'required'],
            [['moduleNamespace', 'class'], 'string'],
            [['jsonObjects'], 'safe'],
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'JSON page generator';
    }

    /**
     * @return array|\yii\gii\CodeFile[]
     */
    public function generate()
    {
        $jsonFieldConfigString = "[\n";
        $jsonFieldOptions = [];
        $jsonObjects = [];
        $attributes = [];
        $jsonDefaultData = [];
        foreach ($this->jsonObjects as $jsonObject) {
            $attribute = $jsonObject['attribute'];
            $json = $jsonObject['json'];
            $attributes[] = $attribute;
            $schema = $this->generateSchema($json);
            $jsonObjects[$attribute] = $this->generateStringFromArray($schema, 0, null, $attribute);
            $jsonDefaultData[$attribute] = $json;
            $jsonFieldOptions[$attribute] = $this->generateJsonOptions($json);

            $jsonFieldConfigString .= "    '$attribute' => " . $jsonObjects[$attribute] . ",\n";
        }
        $jsonFieldConfigString .= "\n]";

        $jsonFieldOptionsString = $this->convertJsonOptionsArrayToString($jsonFieldOptions);

        // used for generating template files, UNIX system paths
        $modulePath = $this->getModulePath();
        $modelPath = $modulePath . '/models/' . $this->class . '.php';
        $controllerPath = $modulePath . '/controllers/' . $this->class . 'Controller.php';
        $jsonDefaultDataPath = $modulePath . '/models/json/' . $this->class;

        // used inside templates - PHP namespaces or etc
        $controllerNamespace = $this->moduleNamespace . '\\controllers';
        $modelNamespace = $this->moduleNamespace . '\\models';
        $modelClassName = $modelNamespace . '\\' . $this->class;
        $controllerClass = $this->class . 'Controller';
        $jsonPageBaseControllerClassName = 'dvixi\alpaca\controllers\JsonPageController'; //todo: !!! replace with Yii::getAlias after module publishing
        $jsonBaseModelClassName = 'dvixi\alpaca\models\JsonPage';//todo: !!! replace with Yii::getAlias after module publishing
        $moduleName = $this->getModuleName();

        // Model file
        $files[] = new \yii\gii\CodeFile(
            $modelPath,
            $this->render('../templates/model.php', [
                'jsonBaseModelClassName' => $jsonBaseModelClassName,
                'class' => $this->class,
                'namespace' => $modelNamespace,
                'jsonFieldConfigString' => $jsonFieldConfigString,
                'jsonFieldOptionsString' => $jsonFieldOptionsString,
                'attributes' => $attributes,
                'moduleName' => $moduleName
            ])
        );

        // controller file
        $files[] = new \yii\gii\CodeFile(
            $controllerPath,
            $this->render('../templates/controller.php', [
                'controllerNamespace' => $controllerNamespace,
                'modelClassName' => $modelClassName,
                'modelClass' => $this->class,
                'controllerClass' => $controllerClass,
                'jsonPageBaseControllerClassName' => $jsonPageBaseControllerClassName,
            ])
        );

        // default data from JSON
        foreach ($attributes as $attribute) {
            $jsonSectionFilePath = $jsonDefaultDataPath . '/' . $attribute . '.json';
            $files[] = new \yii\gii\CodeFile(
                $jsonSectionFilePath,
                $this->render('../templates/jsonDefaultData.php', [
                    'content' => $jsonDefaultData[$attribute],
                ])
            );
        }

        return $files;
    }

    /**
     * @param $jsonObjectString string
     * @return []
     */
    protected function generateSchema($jsonObjectString)
    {
        $jsonSchema = \JSONSchemaGenerator\Generator::fromJson($jsonObjectString, [
            'properties_required_by_default' => false,
            'items_schema_collect_mode' => 1
        ]);

        return Json::decode($jsonSchema);
    }

    /**
     * @param $array []
     * @param int $level
     * @param null $parentName
     * @param $attribute string
     * @return string
     */
    protected function generateStringFromArray($array, $level = 0, $parentName = null, $attribute)
    {
        $level++;
        $whitespaces = '    ';
        for ($i = 0; $i < $level; $i++) {
            $whitespaces .= '    ';
        }
        $result = '';

        if (!is_integer($parentName)) {
            $result = "[\n";
        }

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                if (is_integer($key)) {
                    $result .= "$whitespaces    " . $this->generateStringFromArray($item, $level, $key, $attribute) . "\n";
                } else {
                    $result .= "$whitespaces    '$key' => " . $this->generateStringFromArray($item, $level, $key, $attribute) . "\n";
                }
                if ($parentName == 'items') {
                    break; // exit cycle after first array element - schema is not dataSource with content!
                }
            } else {
                if ($parentName != 'properties' && !empty($parentName) && !array_key_exists('title', $array)) {
                    $titleTranslate = "Yii::t('back/$this->class-" . $this->getModuleName() . "-$attribute', '$parentName')";
                    $result .= "$whitespaces    'title' => $titleTranslate,\n";
                    $result .= "$whitespaces    'required' => true,\n";
                }
                $result .= "\n$whitespaces    '$key' =>  '$item',\n";
            }
        }

        if (!is_integer($parentName)) {
            $result .=  "\n" . $whitespaces . "]";
            if ($level != 1) {
                $result .= ',';
            }
        }

        return $result;
    }

    /**
     * @param $jsonObjectString string
     * @return array
     */
    protected function generateJsonOptions($jsonObjectString)
    {
        $schema = $this->generateSchema($jsonObjectString);

        return $this->processJsonOptionsArray($schema);
    }

    /**
     * @param $array []
     * @param int $level
     * @param null|string $parentName
     * @return array
     */
    protected function processJsonOptionsArray($array, $level = 0, $parentName = null)
    {
        $level++;
        $result = [];
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                if ($key == 'properties' && $parentName != 'fields') {
                    $result['fields'] = $this->processJsonOptionsArray($item, $level, $key);
                } elseif ($key == 'properties') {
                    $result = $this->processJsonOptionsArray($item, $level, $key);
                } else {
                    if (!is_integer($key)) {
                        $result[$key] = $this->processJsonOptionsArray($item, $level, $key);
                    }
                }
            } else {
                if ($parentName != 'fields' && $parentName != 'items') {
                    $result['type'] = 'text';
                }
            }
            if (isset($result['items'])) {
                $result['toolbarSticky'] = 'true';
            }
        }

        if (isset($result['fields']) || isset($result['items'])) {
            unset($result['type']);
        }

        return $result;
    }

    /**
     * @param $array []
     * @return string
     */
    protected function convertJsonOptionsArrayToString($array, $level = 0)
    {
        $level++;
        $whitespaces = '    ';
        for ($i = 0; $i < $level; $i++) {
            $whitespaces .= '    ';
        }
        $result = "[\n";

        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $result .= "$whitespaces    '$key' => " .  $this->convertJsonOptionsArrayToString($item, $level) . "\n";
            } else {
                $result .= "$whitespaces    '$key' => '$item',\n";
            }
        }

        if ($level != 1) {
            $result .= "$whitespaces],";
        } else {
            $result .= "$whitespaces];";
        }

        return $result;
    }

    /**
     * @return bool|string
     */
    protected function getModulePath()
    {
        return Yii::getAlias('@' . str_replace('\\', '/', $this->moduleNamespace));
    }

    /**
     * @return string
     */
    protected function getModuleName()
    {
        $modulePath = $this->getModulePath();

        return basename($modulePath);
    }

    /**
     * @param string $text
     * @return bool
     */
    protected function isHtmlContent($text)
    {
        return $text == strip_tags($text);
    }
}