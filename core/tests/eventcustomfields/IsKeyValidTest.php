<?php


namespace tests\eventcustomfields;

use BaseAppTest;
use models\EventCustomFieldDefinitionModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IsKeyValidTest extends BaseAppTest
{
    public function dataForTestBooleanParam()
    {
        return array(
            array('cat',true),
            array('cat ',false),
            array(' cat',false),
            array('cat nip',false),
            array('cat_nip',true),
            array('cat_nip ',false),
            array('Cat_Nip',true),
            array('Cat.Nip',false),
            array('Cat-Nip',false),
            array('Cat=Nip',false),
            array('cat0',true),
            array('cat1',true),
            array('cat2',true),
        );
    }

    /**
     * @dataProvider dataForTestBooleanParam
     */
    public function testBooleanParam($in, $out)
    {
        $this->assertEquals($out, EventCustomFieldDefinitionModel::isKeyValid($in));
    }
}
