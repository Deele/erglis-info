<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m180528_162320_create_user_session
 */
class m180528_162320_create_user_session extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $thisTable = 'user__session';

        $this->createTable(
            $thisTable,
            [
                'id'              => $this->string(100)
                                          ->comment('User session ID'),
                'ip_address'      => $this->string(50)
                                          ->comment('IP address'),
                'related_user_id' => $this->integer()
                                          ->comment('User ID'),
                'expire'          => $this->integer()
                                          ->comment('Expiration data'),
                'data'            => $this->binary(65536)
                                          ->comment('Session data'),
                'created_at'      => $this->dateTime()
                                          ->comment('Created at'),
                'updated_at'      => $this->dateTime()
                                          ->comment('Updated at'),
            ]
        );

        // expire
        $this->addPrimaryKey(
            'PK',
            $thisTable,
            'id'
        );

        // expire
        $this->createIndex(
            'idx-expire',
            $thisTable,
            'expire'
        );

        // related_user_id
        $this->createIndex(
            'idx-related_user_id',
            $thisTable,
            'related_user_id'
        );
        $this->addForeignKey(
            'fk-related_user_id',
            $thisTable,
            'related_user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $thisTable = 'user__session';

        // expire
        $this->dropIndex(
            'idx-expire',
            $thisTable
        );

        // related_user_id
        $this->dropForeignKey(
            'fk-related_user_id',
            $thisTable
        );
        $this->dropIndex(
            'idx-related_user_id',
            $thisTable
        );

        $this->dropTable($thisTable);
    }
}
