<?php

namespace app\modules\events\base;

/**
 * This is the ActiveQuery class for [[\app\modules\events\models\EventType]].
 *
 * @see \app\modules\events\models\EventType
 */
class EventTypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

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
}
