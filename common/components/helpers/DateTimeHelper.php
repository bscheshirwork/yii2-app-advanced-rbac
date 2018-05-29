<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 11.07.17
 * Time: 12:54
 */

namespace components\helpers;


class DateTimeHelper
{

    /**
     * Expand list of dates with new items between dateStart and dateEnd
     * @param array $dateList ['Y-m-d' => true]
     * @param string $dateStart 'Y-m-d'
     * @param string $dateEnd 'Y-m-d'
     * @return array
     */
    public static function expandDates(array $dateList, string $dateStart, string $dateEnd)
    {
        /** @var \DateTime $startAt */
        /** @var \DateTime $stopAt */
        $startAt = min(new \DateTime($dateStart, \Yii::$app->tz), new \DateTime($dateEnd, \Yii::$app->tz));
        $stopAt = max(new \DateTime($dateStart, \Yii::$app->tz), new \DateTime($dateEnd, \Yii::$app->tz));
        for ($date = clone $startAt; $date <= $stopAt; $date->modify('+1 day')) {
            $dateList[$date->format('Y-m-d')] = true;
        }

        return $dateList;
    }

}