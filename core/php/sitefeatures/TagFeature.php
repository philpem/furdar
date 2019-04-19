<?php


namespace sitefeatures;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagFeature extends \BaseSiteFeature
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
        return 'Tag';
    }

    public function getTitle()
    {
        return 'Tag';
    }

    public function getDescription()
    {
        return 'Events can be tagged for easy filtering.';
    }
}
