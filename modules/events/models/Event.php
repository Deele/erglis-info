<?php

namespace app\modules\events\models;

use deele\devkit\base\HasStatusesTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;
use app\modules\events\base\EventQuery;

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
 * @property string $about About. Multi-lingual attribute.
 * @property string $description Description. Multi-lingual attribute.
 * @property string $location Location. Multi-lingual attribute.
 *
 * @property-read string $typeTitle Type title. Multi-lingual attribute.
 *
 * @property EventType $eventType
 * @property EventTranslation[] $eventTranslations
 */
class Event extends ActiveRecord
{
    use HasStatusesTrait;

    const STATUS_CANCELLED = 1;
    const STATUS_POSTPONED = 2;
    const STATUS_RESCHEDULED = 3;
    const STATUS_SCHEDULED = 4;
    const STATUS_ONGOING = 5;
    const STATUS_ENDED = 6;
    protected $_translatedAttributes;
    protected $_translations = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%event}}';
    }

    /**
     * {@inheritdoc}
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }

    public function init()
    {
        parent::init();
        $this->on(
            static::EVENT_BEFORE_VALIDATE,
            [$this, 'validateTranslations']
        );
        $this->on(
            static::EVENT_AFTER_INSERT,
            [$this, 'saveTranslations']
        );
        $this->on(
            static::EVENT_AFTER_UPDATE,
            [$this, 'saveTranslations']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['event_type_id'], 'required'],
            [['event_type_id', 'status'], 'integer'],
            [['begin_at', 'end_at'], 'safe'],
            [['location_degrees'], 'string', 'max' => 255],
            [
                ['event_type_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => EventType::class,
                'targetAttribute' => ['event_type_id' => 'id']
            ],
            [['about', 'description', 'location'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'               => Yii::t('app.modules.events.Event', 'Event ID'),
            'event_type_id'    => Yii::t('app.modules.events.Event', 'Type'),
            'status'           => Yii::t('app.modules.events.Event', 'Status'),
            'location_degrees' => Yii::t('app.modules.events.Event', 'Location - Decimal degrees'),
            'begin_at'         => Yii::t('app.modules.events.Event', 'Begin at'),
            'end_at'           => Yii::t('app.modules.events.Event', 'End at'),
            'created_at'       => Yii::t('app.modules.events.Event', 'Created at'),
            'updated_at'       => Yii::t('app.modules.events.Event', 'Updated at'),
            'about'            => Yii::t('app.modules.events.Event', 'About'),
            'description'      => Yii::t('app.modules.events.Event', 'Description'),
            'location'         => Yii::t('app.modules.events.Event', 'Location'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function getStatuses($language = null)
    {
        return [
            static::STATUS_CANCELLED   => Yii::t(
                'app.modules.events.Event',
                'Cancelled',
                [],
                $language
            ),
            static::STATUS_POSTPONED   => Yii::t(
                'app.modules.events.Event',
                'Postponed',
                [],
                $language
            ),
            static::STATUS_RESCHEDULED => Yii::t(
                'app.modules.events.Event',
                'Rescheduled',
                [],
                $language
            ),
            static::STATUS_SCHEDULED   => Yii::t(
                'app.modules.events.Event',
                'Scheduled',
                [],
                $language
            ),
            static::STATUS_ONGOING     => Yii::t(
                'app.modules.events.Event',
                'Ongoing',
                [],
                $language
            ),
            static::STATUS_ENDED       => Yii::t(
                'app.modules.events.Event',
                'Ended',
                [],
                $language
            ),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventType()
    {
        return $this->hasOne(EventType::class, ['id' => 'event_type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTranslations()
    {
        return $this->hasMany(EventTranslation::class, ['event_id' => 'id']);
    }

    /**
     * @param string $attribute
     * @param string|null $language
     *
     * @return string|null
     */
    public function getTranslatedAttribute($attribute, $language = null)
    {
        $this->ensureTranslatedAttributesLoaded();
        if (!empty($this->_translatedAttributes)) {
            if (is_null($language)) {
                $language = Yii::$app->language;
            }

            // Try specific language
            if (isset($this->_translatedAttributes[$attribute][$language])) {
                return $this->_translatedAttributes[$attribute][$language];
            }

            // Fallback to null language
            if (isset($this->_translatedAttributes[$attribute][null])) {
                return $this->_translatedAttributes[$attribute][null];
            }

            // Fallback to anything else
            $existingLanguage = key($this->_translatedAttributes[$attribute]);
            if (isset($this->_translatedAttributes[$attribute][$existingLanguage])) {
                return $this->_translatedAttributes[$attribute][$existingLanguage];
            }
        }

        return null;
    }

    /**
     * @param string $attribute
     * @param string $value
     * @param string|null $language
     */
    public function setTranslatedAttribute($attribute, $value, $language = null)
    {
        $this->ensureTranslatedAttributesLoaded();
        if (!isset($this->_translatedAttributes[$attribute])) {
            $this->_translatedAttributes[$attribute] = [];
        }
        if (empty($this->_translatedAttributes)) {
            $this->_translatedAttributes[$attribute][null] = $value;
        }
        if (is_null($language)) {
            $language = Yii::$app->language;
        }
        if (!isset($this->_translatedAttributes[$attribute][$language])) {
            $this->_translatedAttributes[$attribute][$language] = [];
        }
        if (
            !isset($this->_translatedAttributes[$attribute][$language]) ||
            (
                isset($this->_translatedAttributes[$attribute][$language]) &&
                $this->_translatedAttributes[$attribute][$language] != $value
            )
        ) {
            $this->_translatedAttributes[$attribute][$language] = $value;
        }
    }

    public function getAbout($language = null)
    {
        return $this->getTranslatedAttribute('about', $language);
    }

    public function setAbout($value, $language = null)
    {
        return $this->setTranslatedAttribute('about', $value, $language);
    }

    public function getDescription($language = null)
    {
        return $this->getTranslatedAttribute('description', $language);
    }

    public function setDescription($value, $language = null)
    {
        return $this->setTranslatedAttribute('description', $value, $language);
    }

    public function getLocation($language = null)
    {
        return $this->getTranslatedAttribute('location', $language);
    }

    public function setLocation($value, $language = null)
    {
        return $this->setTranslatedAttribute('location', $value, $language);
    }

    public function getTypeTitle($language = null)
    {
        return $this->eventType->getTitle($language);
    }

    protected function ensureTranslatedAttributesLoaded()
    {
        if (is_null($this->_translatedAttributes)) {
            $this->_translatedAttributes = [];
            $translations = $this->getEventTranslations()->all();
            foreach ($translations as $translation) {

                /**
                 * @var EventTranslation $translation
                 */
                foreach ($translation->attributes as $attribute => $value) {
                    if (in_array($attribute, ['event_id', 'language'])) {
                        continue;
                    }
                    if (!isset($this->_translatedAttributes[$attribute])) {
                        $this->_translatedAttributes[$attribute] = [];
                    }
                    if (!isset($this->_translatedAttributes[$attribute][$translation->language])) {
                        $this->_translatedAttributes[$attribute][$translation->language] = [];
                    }
                    $this->_translatedAttributes[$attribute][$translation->language] = $value;
                }
                $this->_translations[$translation->language] = $translation;
            }
        }
    }

    /**
     * Create new translations if necessary and validate translations
     */
    protected function validateTranslations()
    {
        if (!empty($this->_translatedAttributes)) {
            foreach ($this->_translatedAttributes as $attribute => $values) {
                foreach ($values as $language => $value) {
                    if (!isset($this->_translations[$language])) {
                        $this->_translations[$language] = new EventTranslation([
                            'language' => $language
                        ]);
                    }
                    $this->_translations[$language]->{$attribute} = $value;
                }
            }
        }
        if (!empty($this->_translations)) {
            foreach ($this->_translations as $translation) {
                if (!$translation->validate()) {
                    foreach ($translation->errors as $attribute => $errors) {
                        foreach ($errors as $error) {
                            $this->addError($attribute, $error);
                        }
                    }
                }
            }
        }
    }

    /**
     * Save translations
     */
    protected function saveTranslations()
    {
        if (!empty($this->_translations)) {
            foreach ($this->_translations as $translation) {
                $translation->event_id = $this->id;
                if (!$translation->save()) {
                    Yii::error(
                        'Could not save EventTranslation: ' .
                        VarDumper::dumpAsString($translation->errors)
                    );
                }
            }
        }
    }
}
