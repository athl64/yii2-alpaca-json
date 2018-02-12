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
}
