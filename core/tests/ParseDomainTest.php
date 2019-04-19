<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ParseDomainTest extends \BaseAppTest
{
    public function isCoveredByCookiesDataProvider()
    {
        return array(
            array('www.hasacalendar.co.uk','hasacalendar.co.uk',true),
            array('www.hasacalendar.co.uk','hasacalendar.com',false),
            array('hasadevcalendar.co.uk:20135','hasadevcalendar.co.uk',true),
            
        );
    }
    
    /**
    * @dataProvider isCoveredByCookiesDataProvider
    */
    public function testIsCoveredByCookies($domain, $cookieDomain, $result)
    {
        $this->app['config']->webCommonSessionDomain = $cookieDomain;
        $p = new \ParseDomain($this->app, $domain);
        $this->assertEquals($result, $p->isCoveredByCookies());
    }
}
