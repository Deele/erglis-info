<?php

namespace app\modules\events\models;

use app\modules\events\base\EventTypeQuery;
use deele\devkit\base\EnsureIdTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "{{%event__type}}".
 *
 * @property int $id Event type ID
 * @property string $title Title. Multi-lingual attribute.
 * @property string $description Description. Multi-lingual attribute.
 *
 * @property Event[] $events
 * @property EventTypeTranslation[] $eventTypeTranslations
 */
class EventType extends ActiveRecord
{
    use EnsureIdTrait;

    protected $_translatedAttributes;
    protected $_translations = [];

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
        return $this->hasMany(Event::class, ['event_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventTypeTranslations()
    {
        return $this->hasMany(EventTypeTranslation::class, ['event_type_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return EventTypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventTypeQuery(get_called_class());
    }

    public function getTitle($language = null)
    {
        return $this->getTranslatedAttribute('title', $language);
    }

    public function setTitle($value, $language = null)
    {
        return $this->setTranslatedAttribute('title', $value, $language);
    }

    public function getDescription($language = null)
    {
        return $this->getTranslatedAttribute('description', $language);
    }

    public function setDescription($value, $language = null)
    {
        return $this->setTranslatedAttribute('description', $value, $language);
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

    protected function ensureTranslatedAttributesLoaded()
    {
        if (is_null($this->_translatedAttributes)) {
            $this->_translatedAttributes = [];
            $translations = $this->getEventTypeTranslations()->all();
            foreach ($translations as $translation) {

                /**
                 * @var EventTranslation $translation
                 */
                foreach ($translation->attributes as $attribute => $value) {
                    if (in_array($attribute, ['event_type_id', 'language'])) {
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
                $translation->event_type_id = $this->id;
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
