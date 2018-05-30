<?php

namespace common\models;

use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;


/**
 * Trait for ordinary conditions of FIELD_UPDATE_AT field
 * Trait BindQueryTrait
 * @package common\models
 */
trait BindQueryTrait
{

    /**
     * Get current CUBS ActiveRecord
     * @param \DateTime|null $dateTime time for top end search. Null is equal php Now. Can be different from MySQL Expression(NOW())
     * @param string|bool $delta correct \DateInterval`s $interval_spec. Null is add default interval
     * true is mark to set last second of day
     * false is no apply delta
     * @see http://php.net/manual/en/dateinterval.construct.php
     * @return array|\yii\db\ActiveRecord|null
     * @throws \Exception
     */
    public function current(\DateTime $dateTime = null, $delta = self::DEFAULT_DELTA_INTERVAL)
    {
        $endDate = ($dateTime ? clone $dateTime : new \DateTime('now', \Yii::$app->tz));
        if ($delta === true) {
            $endDate->setTime(23, 59, 59);
        } elseif ($delta !== false) {
            $endDate->add(new \DateInterval($delta));
        }
        $this->andWhere([
            'or',
            [
                '<=',
                ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_UPDATE_AT,
                $endDate->format('Y-m-d H:i:s'),
            ],
            [
                'and',
                ['is', ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_UPDATE_AT, null],
                [
                    '<=',
                    ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_CREATE_AT,
                    $endDate->format('Y-m-d H:i:s'),
                ],
            ],
        ]);

        return $this->last();
    }

    /**
     * Load last one model by updateAt
     * @see one()
     * @param null $db
     * @return array|\yii\db\ActiveRecord|null
     */
    public function last($db = null)
    {
        $this->orderBy([
            ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_UPDATE_AT => SORT_DESC,
            ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_CREATE_AT => SORT_DESC,
        ]);

        return parent::one($db);
    }

    /**
     * Load first one model by updateAt
     * @see one()
     * @param null $db
     * @return array|\yii\db\ActiveRecord|null
     */
    public function first($db = null)
    {
        $this->orderBy([
            ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_UPDATE_AT => SORT_ASC,
            ($this->modelClass)::tableName() . '.' . ($this->modelClass)::FIELD_CREATE_AT => SORT_ASC,
        ]);

        return parent::one($db);
    }

    /**
     * Add to query latest by time.
     *
     * SELECT id, createdAt, updatedAt From related_table where
     * GREATEST(createdAt, updatedAt) = ( select max(GREATEST(createdAt, updatedAt)) from related_table as i where i.groupField = related_table.groupField )
     *
     * Note: Add ' or related_table.id is null' manually in his ActiveQuery
     *
     * public function joinWithLastRelated(): self
     * {
     *     $this->joinWith([
     *         'relationName' => function (\common\models\RelatedTableQuery $query) {
     *             $query->lastBy('groupField')->orWhere([RelatedTable::tableName() . '.id' => null]);
     *         },
     *     ]);
     *
     *     return $this;
     * }
     *
     * @param $group string the group field of junction table. Group by relation to main table.
     * @throws \yii\base\InvalidConfigException
     * @return ActiveQueryInterface|ActiveQuery|self
     */
    public function lastBy($group)
    {
        /** @var ActiveQuery $subquery */
        $subquery = \Yii::createObject(static::class, [$this->modelClass]);
        $mainAlias = $this->getPrimaryTableName();
        $alias = strtr($subquery->getPrimaryTableName(), ['{{%' => '{{%inner_']);
        $subquery->alias($alias)
            ->select(new \yii\db\Expression('max(GREATEST(COALESCE(' . $alias . '.`createdAt`, 0), COALESCE(' . $alias . '.`updatedAt`, 0)))'))
            ->andWhere($alias . '.`' . $group . '` = ' . $mainAlias . '.`' . $group . '` ');
        $this->andWhere([
            '=',
            'GREATEST(COALESCE(' . $mainAlias . '.`createdAt`, 0), COALESCE(' . $mainAlias . '.`updatedAt`, 0))',
            $subquery,
        ]);

        return $this;
    }
}