<?php

namespace app\base\models;

use app\modules\events\models\Event;
use app\modules\events\models\EventType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * EventSearch represents the model behind the search form of `app\modules\events\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_type_id', 'status'], 'integer'],
            [['begin_at', 'end_at', 'about', 'location'], 'safe'],
        ];
    }

    /**
     * @param bool $displayCount
     * @param bool $includeEmpty
     *
     * @return array
     */
    public static function getEventTypeFilterValues($displayCount = true, $includeEmpty = true)
    {
        $filterValues = [];
        if ($includeEmpty) {
            $filterValues[null] = '-';
        }
        $query = EventType
            ::find();
        $query->orderBy([
            $query->columnName('title') => SORT_ASC
        ]);
        $eventTypes = $query->all();
        foreach ($eventTypes as $eventType) {
            if ($displayCount) {
                $count = Event::find()->eventType($eventType->id)->count();
                $filterValues[$eventType->id] = sprintf(
                    "%s (%d)",
                    $eventType->title,
                    $count
                );
            } else {
                $filterValues[$eventType->id] = $eventType->title;
            }
        }

        return $filterValues;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Event::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            $query->columnName('id')               => $this->id,
            $query->columnName('event_type_id')    => $this->event_type_id,
            $query->columnName('status')           => $this->status,
            $query->columnName('location_degrees') => $this->location_degrees,
            $query->columnName('begin_at')         => $this->begin_at,
            $query->columnName('end_at')           => $this->end_at,
            $query->columnName('created_at')       => $this->created_at,
            $query->columnName('updated_at')       => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', $query->columnName('about'), $this->about])
              ->andFilterWhere(['like', $query->columnName('location'), $this->location])
              ->andFilterWhere(['like', $query->columnName('description'), $this->description]);

        return $dataProvider;
    }
}
