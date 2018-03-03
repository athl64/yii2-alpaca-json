<?php

use unclead\widgets\MultipleInput;
use metalguardian\formBuilder\ActiveFormBuilder;

/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.02.18
 * Time: 17:07
 */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator \dvixi\alpaca\gii\Generator */

$asset = \yii\gii\GiiAsset::register($this);

echo \yii\helpers\Html::tag('hr');
echo \yii\helpers\Html::a('AlpacaJs documentation', 'http://www.alpacajs.org/documentation.html', ['target' =>'_blank']);
echo \yii\helpers\Html::tag('hr');

echo $form->field($generator, 'class')->hint('examples: HomePage, Footer, JeronimoPage');
echo $form->field($generator, 'backModuleNamespace')->hint('examples: backend\modules\homePage');
echo $form->field($generator, 'frontModuleNamespace')->hint('examples: HomePage, Footer, JeronimoPage');
//echo $form->field($generator, 'schema')->textarea();
echo $form->field($generator, 'jsonObjects')->widget(MultipleInput::className(), [
    'min' => 1,
    'addButtonPosition' => MultipleInput::POS_HEADER,
    'columns' => [
        [
            'name'  => 'attribute',
            'type'  => ActiveFormBuilder::INPUT_TEXT,
            'title' => 'Model attribute (section of page)',
            'enableError' => true,
        ],
        [
            'name'  => 'json',
            'type'  => ActiveFormBuilder::INPUT_TEXTAREA,
            'title' => 'Json object',
        ],
    ]
]);