<?php

namespace org\openacalendar\mapbox;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


class Extension extends \BaseExtension
{
    public function getId()
    {
        return 'org.openacalendar.mapbox';
    }

    public function getTitle()
    {
        return 'MapBox';
    }

    public function getDescription()
    {
        return 'MapBox';
    }
}
