<?php

use api1exportbuilders\EventListICalBuilder;
use models\SiteModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventGetICalTest extends \BaseAppTest
{
    public function dataForTestGetIcalLine()
    {
        return array(
                array('LOCATION','Here','LOCATION:Here'."\r\n"),
                array('123456789','123456789012345678901234567890123456789012345678901234567890',
                    '123456789:123456789012345678901234567890123456789012345678901234567890'."\r\n"),
                array('123456789','1234567890123456789012345678901234567890123456789012345678901234',
                    '123456789:1234567890123456789012345678901234567890123456789012345678901234'."\r\n"),
                array('123456789','12345678901234567890123456789012345678901234567890123456789012345',
                    '123456789:12345678901234567890123456789012345678901234567890123456789012345'."\r\n"),
                array('123456789','123456789012345678901234567890123456789012345678901234567890123456',
                    '123456789:12345678901234567890123456789012345678901234567890123456789012345'."\r\n".' 6'."\r\n"),
                array('123456789','1234567890123456789012345678901234567890123456789012345678901234567',
                    '123456789:12345678901234567890123456789012345678901234567890123456789012345'."\r\n".' 67'."\r\n"),
                array('123456789','1234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789',
                    '123456789:12345678901234567890123456789012345678901234567890123456789012345'."\r\n".' 67890123456789012345678901234567890123456789012345678901234567890123456789'."\r\n".' 012345678901234567890123456789'."\r\n"),
                array('LOCATION',"Here,\nThere;\nEverywhere",
                    'LOCATION:Here\\,\\nThere\\;\\nEverywhere'."\r\n"),
                array('LOCATION','26\4 my house',
                    'LOCATION:26\\\\4 my house'."\r\n"),
            );
    }
        
    
    /**
     * @dataProvider dataForTestGetIcalLine
     */
    public function testGetIcalLine($key, $value, $out)
    {
        $site = new SiteModel();
        $ical = new EventListICalBuilder($this->app, $site, 'Europe/London');
        $this->assertEquals($out, $ical->getIcalLine($key, $value));
    }
}
