<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 05.02.18
 * Time: 16:50
 *
 * @var $jsonFieldConfigString string
 * @var $namespace string
 * @var $class string
 * @var $jsonBaseModelClassName string
 * @var $attributes []
 * @var $moduleName string
 * @var $jsonFieldOptionsString string
 *
 */

echo "<?php\n";
?>

namespace <?= $namespace ?>;

use Yii;
use <?= $jsonBaseModelClassName ?> as BaseJsonPage;

class <?= $class ?> extends BaseJsonPage
{
    <?php foreach($attributes as $attribute) : ?>
public $<?= $attribute ?>;
    <?php endforeach; ?>

    /**
    * @inheritdoc
    */
    public function attributeLabels()
    {
        return [
    <?php foreach($attributes as $attribute) : ?>
        '<?= $attribute ?>' => Yii::t('back/<?= $moduleName ?>-<?= $class ?>', '<?= $attribute ?>'),
    <?php endforeach; ?>
    ];
    }

    /**
    * @return string
    */
    public function getTitle():string
    {
        return Yii::t('back/<?= $moduleName ?>-<?= $class ?>', '<?= $class ?>');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
    <?php if (!empty($attributes)) : ?>
        [[<?php foreach($attributes as $attribute) : ?>'<?= $attribute ?>',<?php endforeach; ?>], 'safe'],
    <?php endif; ?>
    ];
    }

    /**
    * Return array of model attributes that used for storing JSON parts
    * @return array
    */
    public function getJsonAttributes():array
    {
        return [
    <?php foreach($attributes as $attribute) : ?>
        '<?= $attribute ?>',
    <?php endforeach; ?>
    ];
    }

    /**
    * Return array with JSON schemas
    * @return array
    */
    public function getJsonConfig():array
    {
        return <?= $jsonFieldConfigString ?>;
    }

    /**
    * @return array
    */
    public function getJsonOptions():array
    {
        return <?= $jsonFieldOptionsString ?>

    }
}
