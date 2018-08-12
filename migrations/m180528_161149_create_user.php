<?php

namespace app\migrations;

use yii\db\Migration;

/**
 * Class m180528_161149_create_user
 */
class m180528_161149_create_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $thisTable = 'user';
        $this->createTable(
            $thisTable,
            [
                'id'                  => $this->primaryKey()
                                              ->comment('User ID'),
                'username'            => $this->string(100)->notNull()
                                              ->unique()
                                              ->comment('Username'),
                'password_hash'       => $this->string(100)->notNull()
                                              ->comment('Password hash'),
                'auth_key'            => $this->string(50)
                                              ->comment('Authorization key'),
                'status'              => $this->smallInteger()->notNull()
                                              ->defaultValue(1)
                                              ->comment('Status'),
                'accepts_newsletters' => $this->boolean()
                                              ->defaultValue(0)
                                              ->comment('Accepts newsletters'),
                'created_at'          => $this->dateTime()->notNull()
                                              ->comment('Created at'),
                'updated_at'          => $this->dateTime()
                                              ->comment('Updated at'),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $thisTable = 'user';
        $this->dropTable($thisTable);
    }
}
