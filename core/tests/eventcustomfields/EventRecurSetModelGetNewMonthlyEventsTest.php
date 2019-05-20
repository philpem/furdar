<?php

namespace tests\eventcustomfields;

use models\EventCustomFieldDefinitionModel;
use models\EventModel;
use models\EventRecurSetModel;
use TimeSource;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventRecurSetModelGetNewMontlyEventsTest extends \BaseAppTest
{




    /*
     * Basic test. 2nd Wed.
     *
     */
    public function testCustomFieldChange1()
    {
        $this->app['timesource']->mock(2012, 7, 1, 7, 0, 0);

        $customFieldDefinition1 = new EventCustomFieldDefinitionModel();
        $customFieldDefinition1->setId(1);
        $customFieldDefinition1->setExtensionId('org.openacalendar');
        $customFieldDefinition1->setType('TextSingleLine');
        $customFieldDefinition1->setKey('cats');
        $customFieldDefinition1->setLabel('cats');


        $event = new EventModel();
        $event->setStartAt(getUTCDateTime(2012, 6, 13, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2012, 6, 13, 21, 0, 0));
        $event->setSummary("Event Please");
        $event->setCustomField($customFieldDefinition1, "MANY");
        
        $eventSet = new EventRecurSetModel();
        $eventSet->setTimeZoneName('Europe/London');
        $eventSet->setCustomFields(array($customFieldDefinition1));
        
        $newEvents = $eventSet->getNewMonthlyEventsOnSetDayInWeek($event, 6*31);
        
        $this->assertTrue(count($newEvents) >= 6);

        $this->assertTrue($newEvents[0]->hasCustomField($customFieldDefinition1));
        $this->assertEquals("MANY", $newEvents[0]->getCustomField($customFieldDefinition1));

        $this->assertTrue($newEvents[1]->hasCustomField($customFieldDefinition1));
        $this->assertEquals("MANY", $newEvents[1]->getCustomField($customFieldDefinition1));

        $this->assertTrue($newEvents[2]->hasCustomField($customFieldDefinition1));
        $this->assertEquals("MANY", $newEvents[2]->getCustomField($customFieldDefinition1));

        $this->assertTrue($newEvents[3]->hasCustomField($customFieldDefinition1));
        $this->assertEquals("MANY", $newEvents[3]->getCustomField($customFieldDefinition1));
        
        // DST shift happens here! The cats do not care.
        $this->assertTrue($newEvents[4]->hasCustomField($customFieldDefinition1));
        $this->assertEquals("MANY", $newEvents[4]->getCustomField($customFieldDefinition1));

        $this->assertTrue($newEvents[5]->hasCustomField($customFieldDefinition1));
        $this->assertEquals("MANY", $newEvents[5]->getCustomField($customFieldDefinition1));
    }
}
