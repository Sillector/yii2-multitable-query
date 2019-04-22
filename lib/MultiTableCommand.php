<?php
/**
 * Created by PhpStorm.
 * User: mypc
 * Date: 26.08.18
 * Time: 1:15
 */

namespace app\components;


use yii\db\Command;
use yii\db\Exception;

class MultiTableCommand extends Command
{


    /**
     * Установка атрибута ATTR_FETCH_TABLE_NAMES для pdo
     * {@inheritdoc}
     */
    public function prepare($forRead = null)
    {
        if ($this->pdoStatement) {
            $this->bindPendingParams();
            return;
        }

        $sql = $this->getSql();

        if ($this->db->getTransaction()) {
            // master is in a transaction. use the same connection.
            $forRead = false;
        }
        if ($forRead || $forRead === null && $this->db->getSchema()->isReadQuery($sql)) {
            $pdo = $this->db->getSlavePdo();
        } else {
            $pdo = $this->db->getMasterPdo();
        }
        $pdo->setAttribute(\PDO::ATTR_FETCH_TABLE_NAMES, true);

        try {
            $this->pdoStatement = $pdo->prepare($sql);

            $this->bindPendingParams();
        } catch (\Exception $e) {
            $message = $e->getMessage() . "\nFailed to prepare SQL: $sql";
            $errorInfo = $e instanceof \PDOException ? $e->errorInfo : null;
            throw new Exception($message, $errorInfo, (int) $e->getCode(), $e);
        }
    }




}