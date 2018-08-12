<?php

namespace app\modules\events\models;

use Yii;

/**
 * This is the model class for table "{{%event__type_translation}}".
 *
 * @property int $event_type_id Event type ID
 * @property string $language Language
 * @property string $title Title
 * @property string $description Description
 *
 * @property EventType $eventType
 */
class EventTypeTranslation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event__type_translation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_type_id'], 'required'],
            [['event_type_id'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['title'], 'string', 'max' => 255],
            [['event_type_id', 'language'], 'unique', 'targetAttribute' => ['event_type_id', 'language']],
            [['event_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EventType::className(), 'targetAttribute' => ['event_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_type_id' => Yii::t('app.modules.events.EventType', 'Event type ID'),
            'language' => Yii::t('app.modules.events.EventType', 'Language'),
            'title' => Yii::t('app.modules.events.EventType', 'Title'),
            'description' => Yii::t('app.modules.events.EventType', 'Description'),
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
     * {@inheritdoc}
     * @return \app\modules\events\base\EventTypeTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\events\base\EventTypeTranslationQuery(get_called_class());
    }
}
