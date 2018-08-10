<?php

namespace app\base\web;

use Yii;
use yii\db\Expression;
use yii\web\DbSession as YiiDbSession;

class DbSession extends YiiDbSession
{
    public $sessionTable = '{{%user__session}}';

    // 21600 (6h), 3600 (1h) by default.
    public $timeout = 21600;

    public function init()
    {
        parent::init();
        $this->writeCallback = function (DbSession $session) {
            $fields = [
                'related_user_id' => Yii::$app->user->id,
                'ip_address'      => $_SERVER['REMOTE_ADDR'],
                'updated_at'      => new Expression('UTC_TIMESTAMP()'),
            ];
            if (is_null($session->get('created_at'))) {
                $fields['created_at'] = new Expression('UTC_TIMESTAMP()');
            }

            return $fields;
        };
        $this->readCallback = function ($fields) {
            return [
                '__related_user_id' => $fields['related_user_id'],
                '__ip_address' => $fields['ip_address'],
                '__created_at' => $fields['created_at'],
                '__updated_at' => $fields['updated_at'],
            ];
        };
    }
}
