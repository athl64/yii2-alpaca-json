<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.18
 * Time: 14:11
 */

namespace dvixi\alpaca\widgets\alpaca;


use dvixi\alpaca\helpers\JsonPageModuleHelper;
use dvixi\alpaca\widgets\alpaca\assets\AlpacaAsset;
use dvixi\alpaca\widgets\elfinder\InputFile;
use mihaildev\elfinder\ElFinder;
use sadovojav\ckeditor\AssetBundle;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use Yii;

class AlpacaWidget extends InputWidget
{
    const VIEW_NORMAL = 'bootstrap-edit';
    const VIEW_HORIZONTAL = 'bootstrap-edit-horizontal';

    /**
     * used for replacing postRender script inside widget
     */
    const CDATA_POST_RENDER_PART = '<#CDATA_POST_RENDER_PART>';
    /**
     * Used for replacing js scripts from string to JS code functions
     * @var array
     */
    protected $_cdataScriptsReplacements = [];
    /**
     * @var int
     */
    protected $_cdataCounter = 0;

    /**
     * @var array
     */
    public $jsonSchema;
    /**
     * @var array
     */
    public $jsonOptions;
    /**
     * Options for alpacajs plugin
     * Notice: $jsonOptions and $schema will be merged into $alpacaOptions
     * @var array
     */
    public $alpacaOptions;
    /**
     * Html input options
     * @var array
     */
    public $options;

    /**
     * Selector of a language changing anker.
     * Used to show alert when changing language withous saving form.
     * If false or null - do not show alert.
     * @var string
     */
    public $languageTabSelector = '.alpaca-lang-tab';

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        if (JsonPageModuleHelper::m()->useCkEditor) {
            AssetBundle::register(Yii::$app->view);
        }

        AlpacaAsset::register(Yii::$app->view);
        $this->view->registerJs($this->getScript());

        $options = ArrayHelper::merge($this->options, ['id' => $this->getInputId()]);
        $field = Html::activeInput('hidden', $this->model, $this->attribute, $options);
        $alpacaTag = Html::tag('div', $field, ['id' => $this->getAlpacaTagId()]);

        return $alpacaTag;
    }

    /**
     * @return string
     */
    protected function getInputId()
    {
        return Html::getInputId($this->model, $this->attribute);
    }

    /**
     * @return string
     */
    protected function getAlpacaTagId()
    {
        return $this->getInputId() . '-alpaca';
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    protected function getScript()
    {
        $alpacaOptions = $this->alpacaOptions;
        if ($this->jsonOptions) {
            $jsonOptions = $this->jsonOptions;
            if (JsonPageModuleHelper::m()->useCkEditor) {
                $jsonOptions = $this->processOptionsInternal($jsonOptions);
            }
            $alpacaOptions = ArrayHelper::merge($alpacaOptions, [
                'options' => $jsonOptions,
            ]);
        }
        if ($this->jsonSchema) {
            $alpacaOptions = ArrayHelper::merge($alpacaOptions, [
                'schema' => $this->jsonSchema,
            ]);
        }
        if (!isset($alpacaOptions['view'])) {
            $alpacaOptions['view'] = self::VIEW_HORIZONTAL;
        }
        $postRenderCallback = '';
        $inputId = $this->getInputId();
        $fieldId = $this->getAlpacaTagId();
        $alpacaOptions['data'] = $this->model->{$this->attribute};
        $alpacaOptions['postRender'] = 'function(control) {
            control.on("change", function(e) {
                var val = JSON.stringify(this.getValue());
                $(\'#' . $inputId . '\').val(val);
                triggerAlpacaFormChange("' . $fieldId . '");
            });
        }';
        if (!empty($alpacaOptions['postRender'])) {
            $postRenderCallback = $alpacaOptions['postRender'];
            $alpacaOptions['postRender'] = self::CDATA_POST_RENDER_PART;
        }
        $jsonData = Json::encode($alpacaOptions);
        $jsonData = str_replace('"' . self::CDATA_POST_RENDER_PART . '"', $postRenderCallback, $jsonData);
        $jsonData = $this->replaceJsCodeSnippets($jsonData);
        $script = "$('#$fieldId').alpaca($jsonData);\n";
        if ($this->languageTabSelector) {
            $script .= "checkAlpacaLanguageTabs('" . $this->languageTabSelector . "', '" . $fieldId . "');";
        }

        return $script;
    }

    /***
     * Searches inside options ckeditor type and appends elfinder browser config
     *
     * @param $options []
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    protected function processOptionsInternal($options)
    {
        $result = [];
        foreach ($options as $key => $option) {
            if (is_array($option)) {
                $result[$key] = $this->processOptionsInternal($option);
            } else {
                $result[$key] = $option;
            }
            // insert config with elfinder for ckeditor
            if (isset($result['type']) && $result['type'] == 'ckeditor') {
                $ckeditorOptions = [];
                if (isset($result['ckeditor'])) {
                    $ckeditorOptions = $result['ckeditor'];
                }
                $elFinderPath = JsonPageModuleHelper::m()->elFinderControllerPath;
                $ckeditorOptions = ElFinder::ckeditorOptions([$elFinderPath], $ckeditorOptions);
                $result['ckeditor'] = $ckeditorOptions;
            }
            // insert config fot file select widget
            if (isset($result['data']['melonfile'])) {
                $fileWidget = new InputFile([
                    'model' => $this->model,
                    'attribute' => $this->attribute,
                    'multiple' => false,
                ]);
                $fileWidget->run();
                $result['data'] = [
                    'melonfilefield_browse' => Yii::t('app', 'Browse file'),
                    'melonfile_browser_url' => $fileWidget->getManagerOptions()['url'],
                ];
                $jsCode = 'processAlpacaOptions';
                $result['events'] = [
                    'ready' => $this->getCdataIdentifier($jsCode),
                ];
            }
        }

        return $result;
    }

    /**
     * @param $string
     * @return mixed
     */
    protected function replaceJsCodeSnippets($string)
    {
        foreach ($this->_cdataScriptsReplacements as $identifier => $jsCode) {
            $string = str_replace('"' . $identifier . '"', $jsCode, $string);
        }

        return $string;
    }

    /**
     * @param $jsCode
     * @return string
     */
    protected function getCdataIdentifier($jsCode)
    {
        $identifier = '<#CDATA_JS_CODE_BLOCK_' . $this->_cdataCounter . '#>';
        $this->_cdataScriptsReplacements[$identifier] = $jsCode;
        $this->_cdataCounter++;

        return $identifier;
    }

    /**
     * @param string $identifier
     * @return mixed|string
     */
    protected function getJsCodeByIdentifier($identifier)
    {
        return $this->_cdataScriptsReplacements[$identifier] ?? '';
    }
}