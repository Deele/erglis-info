<?php

namespace app\modules\events\models;

use app\modules\events\base\EventTranslationQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%event__translation}}".
 *
 * @property int $event_id Event ID
 * @property string $language Language
 * @property string $about About
 * @property string $description Description
 * @property string $location Location
 *
 * @property Event $event
 */
class EventTranslation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event__translation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_id'], 'integer'],
            [['description'], 'string'],
            [['language'], 'string', 'max' => 5],
            [['about', 'location'], 'string', 'max' => 255],
            [['event_id', 'language'], 'unique', 'targetAttribute' => ['event_id', 'language']],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Event::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'event_id' => Yii::t('app.modules.events.Event', 'Event ID'),
            'language' => Yii::t('app.modules.events.Event', 'Language'),
            'about' => Yii::t('app.modules.events.Event', 'About'),
            'description' => Yii::t('app.modules.events.Event', 'Description'),
            'location' => Yii::t('app.modules.events.Event', 'Location'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Event::class, ['id' => 'event_id']);
    }

    /**
     * {@inheritdoc}
     * @return EventTranslationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventTranslationQuery(get_called_class());
    }
}
