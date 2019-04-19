<?php


use models\ImportModel;
use models\SiteModel;
use import\ImportLanyrdHandler;
use import\ImportRun;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLLanyrdTest extends \BaseAppTest
{
    public function dataForTestIsValid()
    {
        return array(
                array(2012,'http://madlab.org.uk/?ical',false,'xx'),
                array(2012,'http://lanyrd.com/2012/asyncjs-hypermedia/',true,'http://lanyrd.com/2012/asyncjs-hypermedia/asyncjs-hypermedia.ics'),
            );
    }
    
    /**
     *
     * @group import
     * @dataProvider dataForTestIsValid
     */
    public function testIsValid($currentYear, $url, $result, $newURL)
    {
        $this->app['timesource']->mock($currentYear);
        

        $import = new ImportModel();
        $import->setUrl($url);
        $site = new SiteModel();
        $importRun = new ImportRun($this->app, $import, $site);
        
        
        $handler = new ImportLanyrdHandler($this->app);
        $handler->setImportRun($importRun);
        $this->assertEquals($result, $handler->canHandle());
        if ($result) {
            $this->assertEquals($newURL, $handler->getNewFeedURL());
        }
    }
}
