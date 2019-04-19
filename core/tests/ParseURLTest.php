<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ParseURLTest extends \BaseAppTest
{
    public function CanonicalDataProvider()
    {
        return array(
            array('http://test.com/ContactUs','http://test.com/ContactUs?'),
            array('http://TEST.COM/ContactUs','http://test.com/ContactUs?'),
            array('http://TEST.COM/contactus','http://test.com/contactus?'),
            array('http://TEST.COM/','http://test.com/?'),
            array('http://TEST.COM','http://test.com/?'),
        );
    }
    
    /**
    * @dataProvider CanonicalDataProvider
    */
    public function testCanonical($in, $out)
    {
        $p = new \ParseURL($in);
        $this->assertEquals($out, $p->getCanonical());
    }
}
