<?php

namespace Library\Phalcon\Mvc\Migration;

use Phalcon\Db as PhalconDb;

/**
 * Class JavascriptTemplate
 *
 * @property \Phalcon\Db\Adapter\Pdo\Mysql $_connection
 * @package Library\Phalcon\Mvc\Migration
 */
trait JavascriptTemplate
{

    /**
     *
     */
    public function up()
    {

    }

    /**
     * @throws \Exception
     */
    public function afterUp()
    {
        /** @var \Phalcon\Db\Adapter\Pdo\Mysql $connection */
        $connection = static::$_connection;

        try {
            $dataList = $this->_getData();
            if (empty($dataList) || !is_array($dataList)) {
                throw new \Exception('Data migration are empty');
            }

            $connection->begin();

            foreach ($dataList as $data) {
                $name = $data['name'];
                $sql = sprintf('SELECT * FROM `%s` WHERE `name` = ? LIMIT 1;', $this->_getTableName());
                $result = $connection->fetchOne($sql, PhalconDb::FETCH_ASSOC, [$name]);

                $fields = array_keys($data);
                $values = array_values($data);
                if (empty($result)) {
                    $connection->insert($this->_getTableName(), $values, $fields);
                } else {
                    $connection->update($this->_getTableName(), $fields, $values, "name = '{$name}'");
                }
            }

            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw $e;
        }
    }

    /**
     * @return string table name
     */
    protected function _getTableName()
    {
        return 'javascript_templates';
    }

    /**
     * @return array
     */
    abstract protected function _getData();

}
