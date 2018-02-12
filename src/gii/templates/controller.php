<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 2/7/18
 * Time: 11:05 PM
 *
 * @var $controllerNamespace string
 * @var $modelClassName string
 * @var $modelClass string
 * @var $controllerClass string
 * @var $jsonPageBaseControllerClassName string
 */
?>
<?= "<?php\n" ?>

namespace <?= $controllerNamespace ?>;


use <?= $modelClassName ?>;
use <?= $jsonPageBaseControllerClassName ?>;

class <?= $controllerClass ?> extends JsonPageController
{
    /**
     * @return string
     */
    public function getClassName()
    {
        return <?= $modelClass ?>::className();
    }
}
