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
 * @var $controllerBaseClass string
 */
?>
<?= "<?php\n" ?>

namespace <?= $controllerNamespace ?>;


use <?= $modelClassName ?>;
use <?= $controllerBaseClass ?> as BaseController;
use dvixi\alpaca\components\BackendControllerInterface;
use dvixi\alpaca\components\UpdateAction;
use yii\helpers\ArrayHelper;

class <?= $controllerClass ?> extends BaseController implements BackendControllerInterface
{
    /**
     * @return string
     */
    public function getModelClass()
    {
        return <?= $modelClass ?>::class;
    }

    /**
    * @inheritdoc
    */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            'update' => [
                'class' => UpdateAction::class
            ]
        ]);
    }
}
