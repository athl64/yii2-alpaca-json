Yii2 alpacajs widget
=====================

#### NOTICE: module is under development, be carefull when using it!

Module allow to generate CRUD for given JSON and to store it in DB using json field (MySQL 5.7+, POSTGRESQL, etc).
Based on [AlpacaJS](http://www.alpacajs.org/documentation.html).
Supports CkEditor for WYSIWYG editing and ElFinder for file management.

## Install

Using [composer](http://getcomposer.org/download/).

```
php composer.phar require dvixi/yii2-alpaca-json "*"
```

1. add module to main.php config
````
'jsonPage' => [
    'class' => 'dvixi\alpaca\Module',
    'languages' => ['en'],
    'defaultLanguage' => 'en',
],
````
2. or, add languages in bootstrap.php (if storing languages in DB with ActiveRecord)
````
\Yii::$container->set(
    'dvixi\alpaca\Module',
    function ($container, $params, $config) {
        $config['languages'] = \common\helpers\LanguageHelper::getApplicationLanguages();
        $config['defaultLanguage'] = \common\helpers\LanguageHelper::getDefaultLanguage()->locale;

        return new \dvixi\alpaca\Module('jsonPage', null, $config);
    }
);
````
3. for using with ckeditor and file management add to composer.json:
````
"sadovojav/yii2-ckeditor": "dev-master",
"mihaildev/yii2-elfinder": "^1.2",
````

##### [Docs are in progress.]