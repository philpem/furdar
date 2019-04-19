<?php

use symfony\form\MagicUrlTypeFixer;

use Symfony\Component\Form\FormEvent;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MagicUrlTypeFixerTest extends \BaseAppTest
{
    public function dataForTest1()
    {
        return array(
            array("google.co.uk", "http://google.co.uk"),
            array("http://google.co.uk", "http://google.co.uk"),
            array("https://google.co.uk", "https://google.co.uk"),
            array("james@example.com", "mailto:james@example.com"),
            array("mailto:james@example.com", "mailto:james@example.com"),
        );
    }

    /**
     * @dataProvider dataForTest1
     */
    public function test1($in, $out)
    {
        $urlTypeFixer = new MagicUrlTypeFixer();
        $event = new DummyFormEvent($in);
        $urlTypeFixer->onSubmit($event);
        $this->assertEquals($out, $event->getData());
    }
}

class DummyFormEvent extends Symfony\Component\Form\FormEvent
{
    public function __construct($data)
    {
        $this->data = $data;
    }
}
