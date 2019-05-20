<?php

use sysadmin\ActionParser;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ActionParserTest extends BaseAppTest
{
    public function noParamDataProvider()
    {
        return array(
            array("cat   ","cat"),
            array("CAT   ","cat"),
        );
    }
    
    /**
    * @dataProvider noParamDataProvider
    */
    public function testNoParam($in, $out)
    {
        $ap = new ActionParser($in);
        $this->assertEquals($out, $ap->getCommand());
    }
    
    public function oneParamDataProvider()
    {
        return array(
            array("cat tabby","cat","tabby"),
            array("CAT tabby","cat","tabby"),
        );
    }
    
    /**
    * @dataProvider oneParamDataProvider
    */
    public function testOneParam($in, $out, $param)
    {
        $ap = new ActionParser($in);
        $this->assertEquals($out, $ap->getCommand());
        $this->assertEquals($param, $ap->getParam(0));
    }

    public function booleanParamDataProvider()
    {
        return array(
            array("test t  ",true),
            array("test f  ",false),
            array("test T  ",true),
            array("test F  ",false),
            array("test tr  ",true),
            array("test fa  ",false),
            array("test Tr  ",true),
            array("test Fa  ",false),
            array("test 1  ",true),
            array("test 0  ",false),
            array("test 111  ",true),
            array("test 000  ",false),
            array("TEST 000  ",false),
        );
    }
    
    /**
    * @dataProvider booleanParamDataProvider
    */
    public function testBooleanParam($in, $out)
    {
        $ap = new ActionParser($in);
        $this->assertEquals($out, $ap->getParamBoolean(0));
    }
}
