<?php


use models\ImportedEventModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportedEventModelTest extends \BaseAppTest
{


    /**
     * @group import
     */
    public function testReoccurIfDifferentFromNull1()
    {
        $iem = new ImportedEventModel();
        $this->assertFalse($iem->hasReoccurence());
        $this->assertTrue($iem->setReoccurIfDifferent(array("ical_rrule"=>array("FREQ"=>"WEEKLY"))));
        $this->assertTrue($iem->hasReoccurence());
    }



    public function dataForTestReoccurIfDifferentTrue()
    {
        return array(
            array(array("FREQ"=>"WEEKLY","BYDAY"=>"WE"),array("FREQ"=>"WEEKLY","BYDAY"=>"WE","COUNT"=>5)),
            array(array("FREQ"=>"WEEKLY","BYDAY"=>"WE"),array("FREQ"=>"WEEKLY","BYDAY"=>"SA")),
            array(array("FREQ"=>"WEEKLY","BYDAY"=>"WE"),array("FREQ"=>"WEEKLY","COUNT"=>"5")),
        );
    }

    /**
     * @group import
     * @dataProvider dataForTestReoccurIfDifferentTrue
     */
    public function testReoccurIfDifferentTrue($first, $second)
    {
        $iem = new ImportedEventModel();
        $iem->setReoccur(array('ical_rrule'=>$first));
        $this->assertTrue($iem->setReoccurIfDifferent(array('ical_rrule'=>$second)));
    }



    public function dataForTestReoccurIfDifferentFalse()
    {
        return array(
            array(array("FREQ"=>"WEEKLY","BYDAY"=>"WE"),array("FREQ"=>"WEEKLY","BYDAY"=>"WE")),
        );
    }

    /**
     * @dataProvider dataForTestReoccurIfDifferentFalse
     * @group import
     */
    public function testReoccurIfDifferentFalse($first, $second)
    {
        $iem = new ImportedEventModel();
        $iem->setReoccur(array('ical_rrule'=>$first));
        $this->assertTrue($iem->setReoccurIfDifferent(array('ical_rrule'=>$second)));
    }
}
