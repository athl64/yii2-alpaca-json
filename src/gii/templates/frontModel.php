<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 03.03.18
 * Time: 12:31
 *
 * @var $namespace string
 * @var $class string
 * @var $jsonBaseModelClassName string
 * @var $attributes []
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
}
