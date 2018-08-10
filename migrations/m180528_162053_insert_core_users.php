<?php

use app\models\user\User;
use yii\db\Expression;
use yii\db\Migration;

/**
 * Class m180528_162053_insert_core_users
 */
class m180528_162053_insert_core_users extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert(
            'user',
            ['id', 'username', 'password_hash', 'auth_key', 'status', 'created_at'],
            [
                [
                    1,
                    'developer',
                    User::generatePasswordHash('developer'),
                    User::generateAuthKey(),
                    User::STATUS_PENDING_PASSWORD_CHANGE,
                    new Expression('UTC_TIMESTAMP()'),
                ],
                [
                    2,
                    'admin',
                    User::generatePasswordHash('admin'),
                    User::generateAuthKey(),
                    User::STATUS_PENDING_PASSWORD_CHANGE,
                    new Expression('UTC_TIMESTAMP()'),
                ],
            ]
        );
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(
            'user',
            [
                'in',
                'id',
                [
                    1,
                    2,
                ]
            ]
        );
        return true;
    }
}
