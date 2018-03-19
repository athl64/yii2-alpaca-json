<?php

namespace backend\modules\moduleName\models;

use Yii;
use dvixi\alpaca\models\BackJsonPage as BaseJsonPage;

class HomePage extends BaseJsonPage
{
    public $pageSeo;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pageSeo' => Yii::t('back/moduleName-HomePage', 'pageSeo'),
        ];
    }

    /**
     * @return string
     */
    public function getTitle():string
    {
        return Yii::t('back/moduleName-HomePage', 'HomePage');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pageSeo',], 'safe'],
        ];
    }

    /**
     * Return array of model attributes that used for storing JSON parts
     * @return array
     */
    public function getJsonAttributes():array
    {
        return [
            'pageSeo',
        ];
    }

    /**
     * Return array with JSON schemas
     * @return array
     */
    public function getJsonConfig():array
    {
        return [
            'pageSeo' => [

                '$schema' =>  'http://json-schema.org/draft-04/schema#',

                'type' =>  'object',
                'properties' => [
                    'page-seo' => [
                        'title' => Yii::t('back/HomePage-moduleName-pageSeo', 'page-seo'),
                        'required' => true,

                        'type' =>  'object',
                        'properties' => [
                            'title' => [
                                'title' => Yii::t('back/HomePage-moduleName-pageSeo', 'title'),
                                'required' => true,

                                'type' =>  'string',

                            ],
                            'description' => [
                                'title' => Yii::t('back/HomePage-moduleName-pageSeo', 'description'),
                                'required' => true,

                                'type' =>  'string',

                            ],
                            'noindex' => [
                                'title' => Yii::t('back/HomePage-moduleName-pageSeo', 'noindex'),
                                'required' => true,

                                'type' =>  'boolean',

                            ],
                            'html' => [
                                'title' => Yii::t('back/HomePage-moduleName-pageSeo', 'html'),
                                'required' => true,

                                'type' =>  'string',

                            ],
                            'image' => [
                                'title' => Yii::t('back/HomePage-moduleName-pageSeo', 'image'),
                                'required' => true,

                                'type' =>  'string',

                            ],

                        ],

                    ],

                ],

            ],

        ];
    }

    /**
     * @return array
     */
    public function getJsonOptions():array
    {
        return [
            'pageSeo' => [
                'fields' => [
                    'page-seo' => [
                        'fields' => [
                            'title' => [
                                'type' => 'text',
                            ],
                            'description' => [
                                'type' => 'textarea',
                            ],
                            'html' => [
                                'type' => 'ckeditor',
                            ],
                            'noindex' => [
                                'type' => 'checkbox',
                            ],
                            'image' => [
                                'type' => 'text',
                                'data' => [
                                    'melonfile' => 1
                                ]
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
