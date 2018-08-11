<?php

namespace app\modules\events\models;

use Yii;

/**
 * This is the model class for table "{{%event__type}}".
 *
 * @property int $id Event type ID
 *
 * @property Event[] $events
 * @property EventTypeTranslation[] $eventTypeTranslations
 */
class EventType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event__type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.modules.events.EventType', 'Event type ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['event_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTypeTranslations()
    {
        return $this->hasMany(EventTypeTranslation::className(), ['event_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\events\base\EventTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\events\base\EventTypeQuery(get_called_class());
    }
}
