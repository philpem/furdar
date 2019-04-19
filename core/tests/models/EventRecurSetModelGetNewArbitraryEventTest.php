<?php

namespace tests\models;

use models\EventModel;
use models\EventRecurSetModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventRecurSetModelGetNewArbitraryEventTest extends \BaseAppTest
{
    public function dataForTestIsDateToSoonForArbitraryDate()
    {
        return array(
                array(2014,4,1,true),
                array(2015,4,1,false),
            );
    }

    /**
     * @dataProvider dataForTestIsDateToSoonForArbitraryDate
     */
    public function testIsDateToSoonForArbitraryDate($year, $month, $day, $result)
    {
        $timeSource = new \TimeSource();
        $timeSource->mock(2015, 03, 01, 10, 0, 0);

        $eventSet = new EventRecurSetModel();
        $eventSet->setTimeZoneName('Europe/London');

        $newDate = new \DateTime();
        $newDate->setDate($year, $month, $day);

        $this->assertEquals($result, $eventSet->isDateToSoonForArbitraryDate($newDate, $timeSource));
    }



    public function dataForTestIsDateToLateForArbitraryDate()
    {
        return array(
                // test dates in past aren't to late
                array(2014,4,1,10,false),
                // real tests now
                array(2015,4,1,10,true),
                array(2015,4,1,40,false),
            );
    }

    /**
     * @dataProvider dataForTestIsDateToLateForArbitraryDate
     */
    public function testIsDateToLateForArbitraryDate($year, $month, $day, $days, $result)
    {
        $timeSource = new \TimeSource();
        $timeSource->mock(2015, 03, 01, 10, 0, 0);

        $eventSet = new EventRecurSetModel();
        $eventSet->setTimeZoneName('Europe/London');

        $newDate = new \DateTime();
        $newDate->setDate($year, $month, $day);

        $this->assertEquals($result, $eventSet->isDateToLateForArbitraryDate($newDate, $timeSource, $days));
    }
}
