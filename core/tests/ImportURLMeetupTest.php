<?php

use models\ImportModel;
use models\SiteModel;
use import\ImportMeetupHandler;
use import\ImportRun;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLMeetupTest extends \BaseAppTest
{
    public function dataForTestIsValid()
    {
        return array(
                array('http://madlab.org.uk/?ical',false,'xx'),
                array('http://www.meetup.com/HNLondon/',true,'http://www.meetup.com/HNLondon/events/ical/x/'),
                array('http://www.meetup.com/ORG-London/events/74016272/',true,'http://www.meetup.com/ORG-London/events/74016272/ical/x.ics'),
            );
    }
    
    /**
     *
     * @group import
     * @dataProvider dataForTestIsValid
     */
    public function testIsValid($url, $result, $newURL)
    {
        $import = new ImportModel();
        $import->setUrl($url);
        $site = new SiteModel();
        $importRun = new ImportRun($this->app, $import, $site);
        
        
        $handler = new ImportMeetupHandler($this->app);
        $handler->setImportRun($importRun);
        $this->assertEquals($result, $handler->canHandle());
        if ($result) {
            $this->assertEquals($newURL, $handler->getNewFeedURL());
        }
    }
}
