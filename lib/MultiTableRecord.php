<?php
/**
 * Created by PhpStorm.
 * User: mypc
 * Date: 26.08.18
 * Time: 0:48
 */

namespace app\components;


use app\modules\gifts\models\UserGift;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

abstract class MultiTableRecord
{

    /**
     * Массив соотетствий названий таблиц и моделей
     *
     * @return array
     */
    abstract static function models();


    /**
     * Запрос на join таблиц
     *
     * @param MultiTableQuery $query
     * @return mixed
     */
    abstract static function join(MultiTableQuery $query);
    /**
     * {@inheritdoc}
     * @return \yii\db\Query
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        $query = (new MultiTableQuery());
        $query->modelClasses = static::models();
        $query->multiModelClass = static::class;
        return static::join($query);
    }

}