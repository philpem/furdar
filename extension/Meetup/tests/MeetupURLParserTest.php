<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MeetupURLParserTest extends \BaseAppTest
{
    public function dataForTest1()
    {
        return array(
            array('http://www.meetup.com/Edinburgh-Mobile-Dev-Meetup/events/229930153/','Edinburgh-Mobile-Dev-Meetup','229930153'),
            array('http://www.meetup.com/Edinburgh-Mobile-Dev-Meetup/','Edinburgh-Mobile-Dev-Meetup',null),
            array('http://ican.openacalendar.org/',null,null),
        );
    }

    /**
     * @dataProvider dataForTest1
     */
    public function test1($url, $group, $event)
    {
        $meetupURLParser = new \org\openacalendar\meetup\MeetupURLParser($url);
        if (is_null($group)) {
            $this->assertNull($meetupURLParser->getGroupName());
        } else {
            $this->assertEquals($group, $meetupURLParser->getGroupName());
        }
        if (is_null($event)) {
            $this->assertNull($meetupURLParser->getEventId());
        } else {
            $this->assertEquals($event, $meetupURLParser->getEventId());
        }
    }
}
