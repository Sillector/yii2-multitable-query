<?php
/**
 * Created by PhpStorm.
 * User: mypc
 * Date: 26.08.18
 * Time: 1:12
 */

namespace app\components;


use Yii;
use yii\db\Command;
use yii\db\Connection;

class MultiTableConnection extends Connection
{

    /**
     * Замена команды
     * {@inheritdoc}
     */
    public $commandClass = 'app\components\MultiTableCommand';

}