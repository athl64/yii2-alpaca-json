<?php

namespace dvixi\alpaca;

use Yii;
use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'dvixi\alpaca\controllers';

    public $languages = ['en'];
    public $defaultLanguage = 'en';

    /***
     * Use third-party CkEditor and ElFinder packages
     * @var bool
     */
    public $useCkEditor = true;
    /**
     * ElFinder path in controller map
     * @var string
     */
    public $elFinderControllerPath = 'elfinder';
    /**
     * Path for form editing view file, default points to module view file
     * @var string
     */
    private $_viewPath = '@dvixi/alpaca/views';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->languages)) {
            throw new InvalidConfigException('You must set at least 1 language for module!');
        }
        if (empty($this->defaultLanguage)) {
            throw new InvalidConfigException('You must set default language!');
        }
        if (!in_array($this->defaultLanguage, $this->languages)) {
            throw new InvalidConfigException('Default language does not listed in $languages!');
        }
    }

    /**
     * @return string
     */
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->getBasePath() . DIRECTORY_SEPARATOR . 'views';
        }
        return $this->_viewPath;
    }

    /**
     * @param string $path
     */
    public function setViewPath($path)
    {
        $this->_viewPath = Yii::getAlias($path);
    }
}
