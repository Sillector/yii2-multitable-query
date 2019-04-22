<?php
/**
 * Created by PhpStorm.
 * User: mypc
 * Date: 26.08.18
 * Time: 8:50
 */

namespace app\components;


use Yii;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\db\Query;
use yii\helpers\VarDumper;

class MultiTableQuery extends Query
{
    public $modelClasses = [];
    public $multiModelClass = null;

    /**
     * Замена компонента подключения базы
     * {@inheritdoc}
     */
    public function createCommand($db = null)
    {

        if ($db === null) {
            $db = Yii::$app->get('custom_db');
        }
        list($sql, $params) = $db->getQueryBuilder()->build($this);

        $command = $db->createCommand($sql, $params);
        $this->setCommandCache($command);

        return $command;
    }

    /**
     * Возвращает массив мультитабличных моделей
     * {@inheritdoc}
     */
    public function populate($rows)
    {
        $models = [];
        foreach ($rows as $row) {

            $multy_model = new $this->multiModelClass;
            foreach ($this->modelClasses as $alias => $class) {

                /* @var $class ActiveRecord */
                $table_rows = array_filter($row, function ($key) use ($alias){
                    return strpos($key, $alias . '.') === 0;
                }, ARRAY_FILTER_USE_KEY);
                $table_row = [];
                array_walk($table_rows, function ($item1, &$key) use ($alias, &$table_row) {
                    $table_row[str_replace($alias . '.', '', $key)] = $item1;

                });
                $model = $class::instantiate($table_row);
                /* @var $modelClass ActiveRecord */
                $modelClass = get_class($model);
                $modelClass::populateRecord($model, $table_row);
                $multy_model->{$alias} = $model;
            }
            $models[] = $multy_model;

        }

        return $models;
    }


    /**
     * Перегенерация одиночного запроса
     * {@inheritdoc}
     */
    public function one($db = null)
    {
        if ($this->emulateExecution) {
            return false;
        }

        $row = $this->createCommand($db)->queryOne();
        if (empty($row))
            return null;
        $row = $this->populate([$row]);
        return $row[0] ?? null;
    }
}