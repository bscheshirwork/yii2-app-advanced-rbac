<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 17.08.17
 * Time: 18:03
 */

namespace components\helpers;


class WorkTimeHelper
{
    /**
     * date/datetime mask to format \DateTime to string date representation
     */
    public const DATE_FORMAT = 'Y-m-d';

    /**
     * Start of work time
     * for \DateTime->setTime(...[hour, minute, second = 0, microseconds = 0])
     */
    public const WORK_TIME_START_SET_TIME = [9, 30];

    /**
     * End of work time
     * for \DateTime->setTime(...[hour, minute, second = 0, microseconds = 0])
     */
    public const WORK_TIME_END_SET_TIME = [18, 30];

    /**
     * Seconds between static::WORK_TIME_START_SET_TIME and static::WORK_TIME_END_SET_TIME
     */
    public const WORK_TIME_SECONDS_IN_WORK_DAY = 9 * 3600;

    /**
     * Modify \DateTime
     */
    public const MODIFY_PLUS_DAY = '+1 day';
    /**
     * Modify \DateTime
     */
    public const MODIFY_MINUS_DAY = '-1 day';

    /**
     * Normalize start time
     * @param \DateTime $startAt
     * @return \DateTime
     */
    public static function normalizeStart(\DateTime $startAt)
    {
        if ($startAt < (clone $startAt)->setTime(...static::WORK_TIME_START_SET_TIME)) {
            $startAt->setTime(...static::WORK_TIME_START_SET_TIME);
        } elseif ($startAt > (clone $startAt)->setTime(...static::WORK_TIME_END_SET_TIME)) {
            $startAt->setTime(...static::WORK_TIME_START_SET_TIME);
            $startAt->modify(self::MODIFY_PLUS_DAY);
        }
        while ($startAt->format('N') > 5) {
            $startAt->setTime(...static::WORK_TIME_START_SET_TIME);
            $startAt->modify(self::MODIFY_PLUS_DAY);
        }

        return $startAt;
    }

    /**
     * Normalize stop time
     * @param \DateTime $stopAt
     * @return mixed
     */
    public static function normalizeStop(\DateTime $stopAt)
    {
        if ($stopAt < (clone $stopAt)->setTime(...static::WORK_TIME_START_SET_TIME)) {
            $stopAt->setTime(...static::WORK_TIME_END_SET_TIME);
            $stopAt->modify(self::MODIFY_MINUS_DAY);
        } elseif ($stopAt > (clone $stopAt)->setTime(...static::WORK_TIME_END_SET_TIME)) {
            $stopAt->setTime(...static::WORK_TIME_END_SET_TIME);
        }
        while ($stopAt->format('N') > 5) {
            $stopAt->setTime(...static::WORK_TIME_END_SET_TIME);
            $stopAt->modify(self::MODIFY_MINUS_DAY);
        }

        return $stopAt;
    }

    /**
     * Normalize start and end time. Set it inside workTime
     * @param \DateTime $startAt
     * @param \DateTime $stopAt
     * @return array
     */
    public static function normalize(\DateTime $startAt, \DateTime $stopAt)
    {
        $startAt = self::normalizeStart($startAt);
        $stopAt = self::normalizeStop($stopAt);
        if ($stopAt < $startAt) {
            $stopAt = clone $startAt;
        }

        return ['startAt' => $startAt, 'stopAt' => $stopAt];
    }

    /**
     * Return a date interval in seconds between start and stop into work time
     * @param \DateTime $startAt
     * @param \DateTime $stopAt
     * @param array|null $extraDateList ['Y-m-d' => true]
     * @return int
     */
    public static function diff(\DateTime $startAt, \DateTime $stopAt, ?array $extraDateList): int
    {
        //same day
        if ($stopAt->format(self::DATE_FORMAT) == $startAt->format(self::DATE_FORMAT)) {
            return $stopAt->getTimestamp() - $startAt->getTimestamp();
        }

        $seconds = (clone $startAt)->setTime(...
                static::WORK_TIME_END_SET_TIME)->getTimestamp() - $startAt->getTimestamp();
        $startAt->setTime(...static::WORK_TIME_START_SET_TIME);
        $first = true;
        while ($stopAt->format(self::DATE_FORMAT) != $startAt->format(self::DATE_FORMAT)) {
            $startAt->modify(self::MODIFY_PLUS_DAY);
            if ($first || ($startAt->format('N') > 5) || ($extraDateList[$startAt->format(self::DATE_FORMAT)] ?? false)) {
                $first = false;
            } else {
                $seconds += self::WORK_TIME_SECONDS_IN_WORK_DAY;
            }
        }
        //last day difference
        $seconds += ($stopAt->getTimestamp() - $startAt->getTimestamp());

        return $seconds;
    }

    /**
     * Calculate (work) time of reaction. Calculate work time of seconds between two datetime objects.
     * Remove weekend time
     * Remove additional dates (i.e. holidays dates)
     * @param null|string $dateStart
     * @param null|string $dateEnd
     * @param array $extraDateList ['Y-m-d' => true]
     * @return int count of seconds
     */
    public static function calculateDiff(
        ?string $dateStart = null,
        ?string $dateEnd = null,
        array $extraDateList = []
    ): int {
        /** @var \DateTime $startAt */
        /** @var \DateTime $stopAt */
        $startAt = min(new \DateTime($dateStart, \Yii::$app->tz), new \DateTime($dateEnd, \Yii::$app->tz));
        $stopAt = max(new \DateTime($dateStart, \Yii::$app->tz), new \DateTime($dateEnd, \Yii::$app->tz));

        // 0. normalize start/stop datetime
        // 1. simple diff
        // 2. remove night
        // 3. remove weekends
        // 4. remove additional weekends
        // 5. remove holidays

        ['startAt' => $startAt, 'stopAt' => $stopAt] = self::normalize($startAt, $stopAt);

        return self::diff($startAt, $stopAt, $extraDateList);
    }

    /**
     * Move time to start-end interval. And add part, who outside it
     * @param \DateTime $dateTime
     * @param array|null $extraDateList ['Y-m-d' => true]
     * @return \DateTime
     */
    public static function shiftForward(\DateTime $dateTime, ?array $extraDateList): \DateTime
    {
        if ($dateTime < ($clone = clone $dateTime)->setTime(...static::WORK_TIME_START_SET_TIME)) {
            $seconds = $clone->getTimestamp() - $dateTime->getTimestamp();
            $dateTime->setTime(...static::WORK_TIME_START_SET_TIME);
            $dateTime->modify('+' . $seconds . ' seconds');
        } elseif ($dateTime > ($clone = clone $dateTime)->setTime(...static::WORK_TIME_END_SET_TIME)) {
            $seconds = $dateTime->getTimestamp() - $clone->getTimestamp();
            $dateTime->setTime(...static::WORK_TIME_START_SET_TIME);
            $dateTime->modify(self::MODIFY_PLUS_DAY);
            $dateTime->modify('+' . $seconds . ' seconds');
        }
        while ($dateTime->format('N') > 5 || ($extraDateList[$dateTime->format(self::DATE_FORMAT)] ?? false)) {
            $dateTime->modify(self::MODIFY_PLUS_DAY);
        }

        return $dateTime;
    }


    /**
     * Returns a seconds between "now" and normalized "now + seconds" - end must be inside workTime
     * @param int $seconds
     * @return int
     */
    public static function shiftEnd(int $seconds): int
    {
        $dateStart = 'now';
        $startAt = new \DateTime($dateStart, \Yii::$app->tz);
        $stopAt = (clone $startAt)->modify('+' . $seconds . ' seconds');
        $weekends = \Yii::$app->googleCalendar->getWeekendDates((clone $startAt)->modify('-1 day'),
            (clone $stopAt)->modify('+1 month'));
        $stopAt = self::shiftForward($stopAt, $weekends);

        return $stopAt->getTimestamp() - $startAt->getTimestamp();
    }
}