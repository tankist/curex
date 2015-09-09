<?php

use Phalcon\Db\RasColumn as Column;
use Phalcon\Db\RasIndex;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class OffersMigration_103 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'offers',
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
                        'offer_type',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'id',
                        ]
                    ),
                    new Column(
                        'currency_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'offer_type',
                        ]
                    ),
                    new Column(
                        'amount',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'notNull' => true,
                            'after' => 'currency_id',
                        ]
                    ),
                    new Column(
                        'start_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'after' => 'amount',
                        ]
                    ),
                    new Column(
                        'end_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'after' => 'start_date',
                        ]
                    ),
                    new Column(
                        'update_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'after' => 'end_date',
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'update_date',
                        ]
                    ),
                    new Column(
                        'rate',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'notNull' => true,
                            'after' => 'user_id',
                        ]
                    ),
                ],
                'indexes' => [
                    new RasIndex('PRIMARY', ['id']),
                    new RasIndex('user_id', ['user_id']),
                    new RasIndex('currency_id', ['currency_id']),
                ],
                'references' => [
                    new Reference('offers_ibfk_2', [
                        'referencedSchema' => 'curex',
                        'referencedTable' => 'currencies',
                        'columns' => ['currency_id'],
                        'referencedColumns' => ['id']
                    ]),
                    new Reference('offers_ibfk_1', [
                        'referencedSchema' => 'curex',
                        'referencedTable' => 'users',
                        'columns' => ['user_id'],
                        'referencedColumns' => ['id']
                    ]),
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
