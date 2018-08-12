<?php

namespace app\modules\events\base;

use app\modules\events\models\Event;
use app\modules\events\models\EventTranslation;
use app\modules\events\models\EventType;
use deele\devkit\db\ActiveQueryHelper;
use deele\devkit\db\ColumnNameTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Event]].
 *
 * @see Event
 */
class EventQuery extends ActiveQuery
{
    use ColumnNameTrait;

    /**
     * @var string
     */
    protected $_joinedEventTranslations;

    /**
     * @param string $column
     *
     * @return string
     */
    public function columnName($column)
    {
        if ((in_array($column, ['about', 'location', 'description']))) {
            return $this->translationsColumnName($column);
        }

        return $column;
    }

    /**
     * {@inheritdoc}
     * @return Event[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Event|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param null|string|int|array $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string|null $conditionType
     *
     * @return EventQuery|ActiveQuery
     */
    public function id($value, $state = true, $conditionType = 'and')
    {
        return ActiveQueryHelper::byNumericValue(
            $this,
            $this->columnName('id'),
            $value,
            $state,
            null,
            $conditionType
        );
    }

    /**
     * @param string $column
     *
     * @return string
     */
    public function translationsColumnName($column)
    {
        if (is_null($this->_joinedEventTranslations)) {
            $this->_joinedEventTranslations = 'eventTranslation';
            $this->leftJoin(
                EventTranslation::tableName() . ' as ' . $this->_joinedEventTranslations,
                sprintf(
                    '%s = %s.event_id',
                    $this->columnName('id'),
                    $this->_joinedEventTranslations
                )
            );
        }

        return $this->_joinedEventTranslations . '.' . $column;
    }

    /**
     * @param string $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string $conditionType
     *
     * @return EventQuery|ActiveQuery
     */
    public function about($value, $state = true, $conditionType = 'and')
    {
        return ActiveQueryHelper::byStringValue(
            $this,
            $this->columnName('about'),
            $value,
            $state,
            false,
            $conditionType
        );
    }

    /**
     * @param string $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string $conditionType
     *
     * @return EventQuery|ActiveQuery
     */
    public function description($value, $state = true, $conditionType = 'and')
    {
        return ActiveQueryHelper::byStringValue(
            $this,
            $this->columnName('description'),
            $value,
            $state,
            false,
            $conditionType
        );
    }

    /**
     * @param string $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string $conditionType
     *
     * @return EventQuery|ActiveQuery
     */
    public function location($value, $state = true, $conditionType = 'and')
    {
        return ActiveQueryHelper::byStringValue(
            $this,
            $this->columnName('location'),
            $value,
            $state,
            false,
            $conditionType
        );
    }

    /**
     * @param Event|int|string|Event[]|int[]|string[] $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string $conditionType
     *
     * @return EventQuery|ActiveQuery
     */
    public function eventType($value, $state = true, $conditionType = 'and')
    {
        return ActiveQueryHelper::byNumericValue(
            $this,
            $this->columnName('event_type_id'),
            $value,
            $state,
            function ($value) {
                return EventType::ensureId($value);
            },
            $conditionType
        );
    }
}
