<?php


namespace tests\api1exportbuilders;

use api1exportbuilders\ICalEventIdConfig;
use BaseAppTest;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ICalEventIdConfigTest extends BaseAppTest
{
    public function dataDefault()
    {
        return array(
            array(null, array()),
            array('an adorable kitten', array()),
            array(null, array('HTTP_USER_AGENT'=>'wget')),
        );
    }

    /**
     * @dataProvider dataDefault
     */
    public function testDefault($option, $server)
    {
        $iCalEventIdConfig = new ICalEventIdConfig($option, $server);
        $this->assertTrue($iCalEventIdConfig->isSlug());
        $this->assertFalse($iCalEventIdConfig->isSlugStartEnd());
    }

    public function dataForTestSlugSetByOption()
    {
        return array(
            array('slug'),
            array('SLUG'),
            array('   slUG  '),
        );
    }

    /**
    * @dataProvider dataForTestSlugSetByOption
    */
    public function testSlugSetbyOption($in)
    {
        $iCalEventIdConfig = new ICalEventIdConfig($in);
        $this->assertTrue($iCalEventIdConfig->isSlug());
        $this->assertFalse($iCalEventIdConfig->isSlugStartEnd());
    }

    public function dataForTestSlugStartEndSetByOption()
    {
        return array(
            array('slugstartend'),
            array('SLUGSTARTEND'),
            array('   slUGstARTenD  '),
        );
    }

    /**
    * @dataProvider dataForTestSlugStartEndSetByOption
    */
    public function testSlugStartEndSetbyOption($in)
    {
        $iCalEventIdConfig = new ICalEventIdConfig($in);
        $this->assertFalse($iCalEventIdConfig->isSlug());
        $this->assertTrue($iCalEventIdConfig->isSlugStartEnd());
    }

    public function testSlugStartEndSetbyUserAgent()
    {
        $iCalEventIdConfig = new ICalEventIdConfig(null, array('HTTP_USER_AGENT'=>'Google-Calendar-Importer'));
        $this->assertTrue($iCalEventIdConfig->isSlug());
        $this->assertFalse($iCalEventIdConfig->isSlugStartEnd());
    }
}
