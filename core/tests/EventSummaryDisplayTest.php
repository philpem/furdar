<?php

use models\EventModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventSummaryDisplayTest extends \BaseAppTest
{
    public function dataForTestWithGroup()
    {
        return array(
                array('My group','Monthly meetup','My group: Monthly meetup'),
                array('My group','','My group'),
                array('EDLUG','EDLUG','EDLUG'),
                array('edlug','EDLUG','EDLUG'),
                array('Monthly meetup','Monthly meetup','Monthly meetup'),
                array('BCS Glasgow','BCS Glasgow Branch Meeting - Optimising virtual keyboards','BCS Glasgow Branch Meeting - Optimising virtual keyboards'),
                array('bcs glasgow','BCS Glasgow Branch Meeting - Optimising virtual keyboards','BCS Glasgow Branch Meeting - Optimising virtual keyboards'),
            );
    }
            
        
    /**
     * @dataProvider dataForTestWithGroup
     */
    public function testWithGroup($groupTitle, $eventSummary, $summaryDisplayOut)
    {
        $event = new EventModel();
        $event->setSummary($eventSummary);
        $event->setGroupTitle($groupTitle);
        $this->assertEquals($summaryDisplayOut, $event->getSummaryDisplay());
    }
}
