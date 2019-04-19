<?php

use models\GroupModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupTwitterUserNameTest extends \BaseAppTest
{
    public function dataForTestSet()
    {
        return array(
                array('jarofgreen','jarofgreen'),
                array('@jarofgreen','jarofgreen'),
                array('twitter@jarofgreen','jarofgreen'),
                array(' @jarofgreen','jarofgreen'),
                array('Follow @jarofgreen','jarofgreen'),
                array('http://twitter.com/#!/jarofgreen','jarofgreen'),
                array('https://twitter.com/#!/jarofgreen','jarofgreen'),
                array('http://twitter.com/jarofgreen','jarofgreen'),
                array('https://twitter.com/jarofgreen','jarofgreen'),
                array('http://www.twitter.com/jarofgreen','jarofgreen'),
                array('twitter.com/jarofgreen','jarofgreen'),
                array('www.twitter.com/jarofgreen','jarofgreen'),
            );
    }
    
    /**
     * @dataProvider dataForTestSet
     */
    public function testSet($set, $result)
    {
        $group = new GroupModel();
        $group->setTwitterUsername($set);
        $this->assertEquals($result, $group->getTwitterUsername());
    }
}
