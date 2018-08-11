<?php

namespace app\modules\events\models;

use Yii;

/**
 * This is the model class for table "{{%event}}".
 *
 * @property int $id Event ID
 * @property int $event_type_id Type
 * @property int $status Status
 * @property string $location_degrees Location - Decimal degrees
 * @property string $begin_at Begin at
 * @property string $end_at End at
 * @property string $created_at Created at
 * @property string $updated_at Updated at
 *
 * @property EventType $eventType
 * @property EventTranslation[] $eventTranslations
 */
class Event extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_type_id'], 'required'],
            [['event_type_id', 'status'], 'integer'],
            [['begin_at', 'end_at', 'created_at', 'updated_at'], 'safe'],
            [['location_degrees'], 'string', 'max' => 255],
            [['event_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventType::className(), 'targetAttribute' => ['event_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.modules.events.Event', 'Event ID'),
            'event_type_id' => Yii::t('app.modules.events.Event', 'Type'),
            'status' => Yii::t('app.modules.events.Event', 'Status'),
            'location_degrees' => Yii::t('app.modules.events.Event', 'Location - Decimal degrees'),
            'begin_at' => Yii::t('app.modules.events.Event', 'Begin at'),
            'end_at' => Yii::t('app.modules.events.Event', 'End at'),
            'created_at' => Yii::t('app.modules.events.Event', 'Created at'),
            'updated_at' => Yii::t('app.modules.events.Event', 'Updated at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventType()
    {
        return $this->hasOne(EventType::className(), ['id' => 'event_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTranslations()
    {
        return $this->hasMany(EventTranslation::className(), ['event_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }
}
