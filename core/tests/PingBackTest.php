<?php
use pingback\ParsePingBack;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class PingBackTest extends \BaseAppTest
{
    public function testParse1()
    {
        $pingback = ParsePingBack::parseFromData('<?xml version="1.0" encoding="iso-8859-1"?>
<methodCall>
  <methodName>pingback.ping</methodName>
  <params>
   <param><value><string>http://www.example.com/index.php?p=71</string></value></param>
   <param><value><string>http://www.example2.com/index.php?p=72</string></value></param>
  </params>
</methodCall>');

        $this->assertEquals("http://www.example.com/index.php?p=71", $pingback->getSourceUrl());
        $this->assertEquals("http://www.example2.com/index.php?p=72", $pingback->getTargetUrl());
    }
}
