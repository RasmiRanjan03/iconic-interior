<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Filter model for the admin lead GridView and CSV export.
 */
class UserQuerySearch extends UserQuery
{
    public $q;
    public $date_from;
    public $date_to;

    public function rules(): array
    {
        return [
            [['id', 'is_read'], 'integer'],
            ['status', 'in', 'range' => self::STATUSES],
            ['query_source', 'in', 'range' => self::SOURCES],
            [['selected_service', 'q'], 'string', 'max' => 160],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function behaviors(): array
    {
        return [];
    }

    public function attributeLabels(): array
    {
        return array_merge(parent::attributeLabels(), [
            'q' => 'Search name / phone / email',
            'date_from' => 'From',
            'date_to' => 'To',
        ]);
    }

    public function buildQuery(array $params): ActiveQuery
    {
        $query = UserQuery::find();
        $this->load($params);
        if (!$this->validate()) {
            return $query->where('0=1');
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'query_source' => $this->query_source,
            'selected_service' => $this->selected_service,
            'is_read' => $this->is_read,
        ]);
        if ($this->q) {
            $query->andWhere(['or',
                ['like', 'full_name', $this->q],
                ['like', 'phone', $this->q],
                ['like', 'email', $this->q],
                ['like', 'preferred_location', $this->q],
            ]);
        }
        if ($this->date_from) {
            $query->andWhere(['>=', 'created_at', $this->date_from . ' 00:00:00']);
        }
        if ($this->date_to) {
            $query->andWhere(['<=', 'created_at', $this->date_to . ' 23:59:59']);
        }
        return $query;
    }

    public function search(array $params): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => $this->buildQuery($params),
            'pagination' => ['pageSize' => 20],
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['id', 'full_name', 'query_source', 'selected_service', 'status', 'is_read', 'created_at', 'estimated_budget'],
            ],
        ]);
    }
}
