<?php

use Phalcon\Db\RasColumn as Column;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;
use Phalcon\Db\RasIndex;

class OffersMigration_100 extends Migration
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
                        'currency',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'offer_type',
                        ]
                    ),
                    new Column(
                        'amount',
                        [
                            'type' => Column::TYPE_DECIMAL,
                            'notNull' => true,
                            'after' => 'currency',
                        ]
                    ),
                    new Column(
                        'start_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after' => 'amount',
                        ]
                    ),
                    new Column(
                        'end_date',
                        [
                            'type' => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'after' => 'start_date',
                        ]
                    ),
                    new Column(
                        'user_id',
                        [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'end_date',
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
                ],
                'references' => [
                    new Reference('offers_ibfk_1', [
                        'referencedSchema' => 'curex',
                        'referencedTable' => 'users',
                        'columns' => array('user_id'),
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
