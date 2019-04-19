<?php


/**
 *
 * A central time source for the app.
 *
 * This is done so the current time can be set to specific values (mocked)
 * for automated testing.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TimeSource
{
    protected static $now = null;

    public static function getFormattedForDataBase()
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        if (TimeSource::$now) {
            $dt->setTimestamp(TimeSource::$now);
        }
        return $dt->format("Y-m-d H:i:s");
    }

    

    public static function time()
    {
        return TimeSource::$now ? TimeSource::$now : time();
    }

    /** @var \DateTime **/
    public static function getDateTime()
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        if (TimeSource::$now) {
            $dt->setTimestamp(TimeSource::$now);
        }
        return $dt;
    }
    
    public static function mock($year=2012, $month=1, $day=1, $hour=0, $minute=0, $second=0)
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        $dt->setTime($hour, $minute, $second);
        $dt->setDate($year, $month, $day);
        TimeSource::$now = $dt->getTimestamp();
    }
    
    public static function realTime()
    {
        TimeSource::$now = null;
    }
}
