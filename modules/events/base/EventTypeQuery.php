<?php

namespace app\modules\events\base;

use app\modules\events\models\EventTypeTranslation;
use deele\devkit\db\ActiveQueryHelper;
use deele\devkit\db\ColumnNameTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\events\models\EventType]].
 *
 * @see \app\modules\events\models\EventType
 */
class EventTypeQuery extends ActiveQuery
{
    use ColumnNameTrait;

    /**
     * @var string
     */
    protected $_joinedEventTypeTranslations;

    /**
     * {@inheritdoc}
     * @return \app\modules\events\models\EventType[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\events\models\EventType|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param string $column
     *
     * @return string
     */
    public function columnName($column)
    {
        if ((in_array($column, ['title', 'description']))) {
            return $this->translationsColumnName($column);
        }

        return $column;
    }

    /**
     * @param string $column
     *
     * @return string
     */
    public function translationsColumnName($column)
    {
        if (is_null($this->_joinedEventTypeTranslations)) {
            $this->_joinedEventTypeTranslations = 'eventTypeTranslation';
            $this->leftJoin(
                EventTypeTranslation::tableName() . ' as ' . $this->_joinedEventTypeTranslations,
                sprintf(
                    '%s = %s.event_type_id',
                    $this->columnName('id'),
                    $this->_joinedEventTypeTranslations
                )
            );
        }

        return $this->_joinedEventTypeTranslations . '.' . $column;
    }

    /**
     * @param string $value
     * @param bool|string $state Either boolean value (for equality) or valid SQL comparison operator
     * @param string $conditionType
     *
     * @return EventQuery|ActiveQuery
     */
    public function title($value, $state = true, $conditionType = 'and')
    {
        return ActiveQueryHelper::byStringValue(
            $this,
            $this->columnName('title'),
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
}
