<?php


namespace sitefeatures;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MapFeature extends \BaseSiteFeature
{
    public function __construct()
    {
        $this->is_on = false;
    }

    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getFeatureId()
    {
        return 'Map';
    }


    public function getTitle()
    {
        return 'Map';
    }

    public function getDescription()
    {
        return 'Maps shows you events on a map to browse around. ';
    }
}
