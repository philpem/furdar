<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TraitCSVTest extends \BaseAppTest
{
    use \api1exportbuilders\TraitCSV;


    public function dataForTestCell()
    {
        return array(
            array('','""'),
            array(null,'""'),
            array('out','"out"'),
            array('out out','"out out"'),
            array('he said "hi"','"he said ""hi"""'),
        );
    }

    /**
     * @dataProvider dataForTestCell
     */
    public function testCell($in, $out)
    {
        $this->assertEquals($out, $this->getCell($in));
    }
}
