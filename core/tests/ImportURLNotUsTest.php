<?php

use models\ImportModel;
use models\SiteModel;
use import\ImportNotUsHandler;
use import\ImportRun;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLNotUsTest extends \BaseAppTest
{
    public function dataForTestIsValid()
    {
        return array(
                # without ports
                array('ican.hasacalendar.co.uk','hasacalendar.co.uk',true,'ican.hasacalendar.co.uk','hasacalendar.co.uk',
                    'http://madlab.org.uk/?ical',false),
                array('ican.hasacalendar.co.uk','hasacalendar.co.uk',true,'ican.hasacalendar.co.uk','hasacalendar.co.uk',
                    'http://opentechcalendar.co.uk/index.php/event/ical/',false),
                array('ican.hasacalendar.co.uk','hasacalendar.co.uk',true,'ican.hasacalendar.co.uk','hasacalendar.co.uk',
                    'http://demo.hasacalendar.co.uk/index.php/event/ical/',true),
                array('ican.hasacalendar.co.uk','hasacalendar.co.uk',true,'ican.hasacalendar.co.uk','hasacalendar.co.uk',
                    'http://ican.hasacalendar.co.uk/index.php/event/ical/',true),
                # With ports
                array('hasadevcalendar.co.uk:20150','hasadevcalendar.co.uk:20151',true,'hasadevcalendar.co.uk:40300','hasadevcalendar.co.uk:40302',
                    'http://www.facebook.com/events/1435905404p890489089045',false),
                array('hasadevcalendar.co.uk:20150','hasadevcalendar.co.uk:20151',true,'hasadevcalendar.co.uk:40300','hasadevcalendar.co.uk:40302',
                    'http://test1.hhasadevcalendar.co.uk:20151/index.php/event/ical/',true),
            );
    }
    
    /**
     *
     * @group import
     * @dataProvider dataForTestIsValid
     */
    public function testIsValid($webIndexDomain, $webSiteDomain, $hasSSL, $webIndexDomainSSL, $webSiteDomainSSL, $url, $result)
    {
        $this->app['config']->webIndexDomain = $webIndexDomain;
        $this->app['config']->webSiteDomain = $webSiteDomain;
        $this->app['config']->hasSSL = $hasSSL;
        $this->app['config']->webIndexDomainSSL = $webIndexDomainSSL;
        $this->app['config']->webSiteDomainSSL = $webSiteDomainSSL;
                
        $import = new ImportModel();
        $import->setUrl($url);
        $site = new SiteModel();
        $importRun = new ImportRun($this->app, $import, $site);
        
        $handler = new ImportNotUsHandler($this->app);
        $handler->setImportRun($importRun);
        $this->assertEquals($result, $handler->canHandle());
    }
}
