<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class RequestClassTest extends \BaseAppTest
{
    public function dataForTestGetGetOrPostBoolean()
    {
        return array(
            // test Defaults
            array( array(), array(), 'test', true, true ),
            array( array(), array(), 'test', false, false ),
            array( array('cat'=>true), array(), 'test', false, false ),
            array( array(), array('cat'=>true), 'test', false, false ),
            // test GET
            array( array('cat'=>'true'), array(), 'cat', false, true ),
            array( array('cat'=>'false'), array(), 'cat', false, false ),
            array( array('cat'=>' TRUE '), array(), 'cat', false, true ),
            array( array('cat'=>' FALSE '), array(), 'cat', false, false ),
            array( array('cat'=>'on'), array(), 'cat', false, true ),
            array( array('cat'=>'off'), array(), 'cat', false, false ),
            array( array('cat'=>'yes'), array(), 'cat', false, true ),
            array( array('cat'=>'no'), array(), 'cat', false, false ),
            array( array('cat'=>'1'), array(), 'cat', false, true ),
            array( array('cat'=>'0'), array(), 'cat', false, false ),
            // test POST
            array( array(),array('cat'=>'true'),  'cat', false, true ),
            array( array(),array('cat'=>'false'),  'cat', false, false ),
            array( array(),array('cat'=>' TRUE '),  'cat', false, true ),
            array( array(),array('cat'=>' FALSE '),  'cat', false, false ),
            array( array(),array('cat'=>'on'),  'cat', false, true ),
            array( array(),array('cat'=>'off'), 'cat', false, false ),
            array( array(),array('cat'=>'yes'),  'cat', false, true ),
            array( array(),array('cat'=>'no'),  'cat', false, false ),
            array( array(),array('cat'=>'1'),  'cat', false, true ),
            array( array(),array('cat'=>'0'),  'cat', false, false ),
            // test GET and POST
            array( array('cat'=>'1'),array('cat'=>'0'),  'cat', false, true ),
        );
    }
    
    /**
    * @dataProvider dataForTestGetGetOrPostBoolean
    */
    public function testGetGetOrPostBoolean($query, $request, $key, $default, $result)
    {
        $request = new Symfony\Component\HttpFoundation\Request($query, $request);
        $ourRequest = new \Request($request);
        $this->assertEquals($result, $ourRequest->getGetOrPostBoolean($key, $default));
    }
}
