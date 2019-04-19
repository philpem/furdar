<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseAddContentToEventShowPage
{
    abstract public function getParameters();

    public function getTemplatesAfterDetails()
    {
        return array();
    }

    public function getTemplatesAtEnd()
    {
        return array();
    }
}
