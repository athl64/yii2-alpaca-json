<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 06.02.18
 * Time: 15:12
 *
 * @var $this \yii\web\View
 * @var $model \dvixi\alpaca\models\JsonPage
 */

$this->title = $model->getTitle();
$tabs = [];
?>
<div class="page-header">
    <h1>
        <?= $model->getTitle() ?>
        <?php if (count(\dvixi\alpaca\helpers\JsonPageModuleHelper::m()->languages) > 1) : ?>
        <div class="btn-group" role="group" aria-label="select language">
            <?php foreach(\dvixi\alpaca\helpers\JsonPageModuleHelper::m()->languages as $language) : ?>
            <?php $isCurrent = $language == $model->lang; ?>
            <?php $languageLabel = $isCurrent ? "[$language]" : $language; ?>
            <?php $btnClass = $isCurrent ? 'btn btn-default active' : 'btn btn-default' ?>
            <?= \yii\helpers\Html::a($languageLabel, \yii\helpers\Url::current(['lang' => $language]), ['class' => $btnClass . ' alpaca-lang-tab']) ?>&nbsp;
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </h1>
</div>

<?php $form = \yii\widgets\ActiveForm::begin(['method' => 'POST']) ?>

    <?php foreach($model->getJsonAttributes() as $attribute) : ?>
    <?php $jsonOptions = empty($model->getJsonOptions()[$attribute]) ? [] : $model->getJsonOptions()[$attribute]; ?>
    <?php $tabs[] = [
            'label' => $model->getAttributeLabel($attribute),
            'content' => \dvixi\alpaca\widgets\alpaca\AlpacaWidget::widget([
                'model' => $model,
                'attribute' => $attribute,
                'jsonSchema' => $model->getJsonConfig()[$attribute] ?? [],
                'jsonOptions' => $jsonOptions
            ])
        ] ?>
    <?php endforeach; ?>
    <?= \yii\bootstrap\Tabs::widget([
        'items' => $tabs,
    ]) ?>

    <button type="submit" class="btn btn-primary" data-alpaca_submit data-check_message="<?= Yii::t('app', 'Check validity of filled form!') ?>"><?= Yii::t('app', 'Save') ?></button>

<?php \yii\widgets\ActiveForm::end(); ?>