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
## Base configuring:

1. add module to main.php config

    ````
    'jsonPage' => [
        'class' => 'dvixi\alpaca\Module',
        'languages' => ['en'],
        'defaultLanguage' => 'en',
    ],
    ````

    or, add languages in bootstrap.php (if storing languages in DB with ActiveRecord)

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

2. for using with ckeditor and file management add to composer.json:
    ````
    "sadovojav/yii2-ckeditor": "dev-master",
    "mihaildev/yii2-elfinder": "^1.2",
    ````
    For ElFinder add this config (to set storage path) into main.php config, components section:
    ````
    'controllerMap' => [
            'elfinder' => [
                'class' => 'mihaildev\elfinder\PathController',
                'access' => ['@'],
                'disabledCommands' => ['netmount'],
                'root' => [
                    'path' => 'uploads/ckeditor',
                    'name' => 'Files',
                ],
                'connectOptions' => [
                    'uploadDeny' => ['*'],
                    'uploadAllow' => ['image/*'],
                ],
            ]
        ],
    ````

3. Add Gii config to config/main-local.php
    ````
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        'controllerNamespace' => 'backend\components\gii\controllers',
        'generators' => [
            ...
            'json-generator' => [
                'class' => 'dvixi\alpaca\gii\Generator',
                'templates' => [
                    'default' => '@dvixi/alpaca/gii/templates'
                ]
            ]
        ],
    ];
    ````
    
## Gii usage

##### Fields:
    Class - name of generated class (same for backend and frontemd)
    Back module namespave - namespace of backend model
    Front module namespave - namespace of frontend model
    Controller base class - full classname with namespace witch will be extended for generating backend controller class
    Json objects:
        Model attribute - name of php class property that will be storing inserted json object
        Json object - Json string with object
        
By default - gii openes with added 'pageSeo' property json, it may be used for storing page SEO data, customised or replaced

Note: yii2 Modules are not generated automatically! You must generate them by default Yii2 generator (and add them to app config), or it may be existing Modules.

## Module architecture

Module generates Backend module files (controller, model and json string files with default data, pasted in 'Json object' field) and Frontend (only model).

Backend files allows editing json with AlpacaJs editor. Multilanguage supported. Backend controller made extendable from Your's own controller to make it fully controllable, for example with access-controlling.

Frontend model allows retrieving json data as php array (using json-decode). If you need raw json - you must override model afterFind() method as it contain replacing raw info with Json::decode() data.

## Backend model configuring and AlpacaJs

Method getJsonAttributes() contain list of properties for storing Json objects, you may change it positions in array to change positions in displayable tabs.

Method rules() by default comes with 'safe' checking (as expected only front validation by AlpacaJS), but you may override this by your own needs.

Method getJsonConfig() - contains Json schema config where gii was inserted field names through Yii::t(). Required rules also was generated for all fields, you can replace it or even remove.

Method getJsonOptions() - most usable config. Through it you can customise field types (text input, file input, textarea, ckeditor, etc) or set AlpacaJS field options.

    Most usable field types: 
        text - simple HTML text input
        textarea - textarea
        email - email field
        checkbox - checkbox input
        ckeditor - ckeditor widget with default config (you may customise it, see AlpacaJs docs)
        file - supported through small hack (data-attribute 'melonfile' and text field type)
        
Example file with all most used field types placed in 'example' folder.