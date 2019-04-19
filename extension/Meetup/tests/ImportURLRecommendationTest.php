<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLRecommendationTest extends \BaseAppTest
{
    public function dataForTest1()
    {
        return array(
            array('http://www.meetup.com/Edinburgh-Mobile-Dev-Meetup/events/229930153/','http://www.meetup.com/Edinburgh-Mobile-Dev-Meetup/'),
            array('http://www.meetup.com/Edinburgh-Mobile-Dev-Meetup/',null),
            array('http://ican.openacalendar.org/',null),
        );
    }

    /**
     * @dataProvider dataForTest1
     */
    public function test1($url, $newURL)
    {
        $importURLRecommendation = new \org\openacalendar\meetup\ImportURLRecommendation($url);
        if (is_null($newURL)) {
            $this->assertFalse($importURLRecommendation->hasNewURL());
        } else {
            $this->assertTrue($importURLRecommendation->hasNewURL());
            $this->assertEquals($newURL, $importURLRecommendation->getNewURL());
        }
    }
}
