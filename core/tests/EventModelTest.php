<?php

use models\EventModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventModelTest extends \BaseAppTest
{
    public function testNoStart()
    {
        $event = new EventModel();
        $event->setEndAt(getUTCDateTime(2013, 8, 01, 17, 0, 0));
        $this->assertEquals(false, $event->validate());
    }
    
    
    public function testNoEnd()
    {
        $event = new EventModel();
        $event->setStartAt(getUTCDateTime(2013, 8, 01, 10, 0, 0));
        $this->assertEquals(false, $event->validate());
    }
    
    public function testFine()
    {
        $event = new EventModel();
        $event->setStartAt(getUTCDateTime(2013, 8, 01, 10, 0, 0));
        $event->setEndAt(getUTCDateTime(2013, 8, 01, 17, 0, 0));
        $this->assertEquals(true, $event->validate());
    }
    
    public function testEndAfterStart()
    {
        $event = new EventModel();
        $event->setStartAt(getUTCDateTime(2013, 8, 01, 18, 0, 0));
        $event->setEndAt(getUTCDateTime(2013, 8, 01, 17, 0, 0));
        $this->assertEquals(false, $event->validate());
    }
}
