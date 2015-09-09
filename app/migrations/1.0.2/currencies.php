<?php

use Phalcon\Db\RasColumn as Column;
use Phalcon\Db\RasIndex;
use Phalcon\Mvc\Model\Migration;

class CurrenciesMigration_102 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'currencies',
            [
                'columns' => [
                    new Column(
                        'id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true,
                        ]
                    ),
                    new Column(
                        'code',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 3,
                            'after' => 'id',
                        ]
                    ),
                    new Column(
                        'title',
                        [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 30,
                            'after' => 'code',
                        ]
                    ),
                ],
                'indexes' => [
                    new RasIndex('PRIMARY', ['id']),
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci',
                ],
            ]
        );
    }

}
