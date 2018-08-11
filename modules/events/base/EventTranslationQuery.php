<?php

namespace app\modules\events\base;

/**
 * This is the ActiveQuery class for [[\app\modules\events\models\EventTranslation]].
 *
 * @see \app\modules\events\models\EventTranslation
 */
class EventTranslationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return \app\modules\events\models\EventTranslation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return \app\modules\events\models\EventTranslation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
