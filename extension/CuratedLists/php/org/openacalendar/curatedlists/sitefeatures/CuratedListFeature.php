<?php


namespace org\openacalendar\curatedlists\sitefeatures;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListFeature extends \BaseSiteFeature
{
    public function __construct()
    {
        $this->is_on = false;
    }

    public function getExtensionId()
    {
        return 'org.openacalendar.curatedlists';
    }

    public function getFeatureId()
    {
        return 'CuratedList';
    }


    public function getTitle()
    {
        return 'Curated Lists';
    }

    public function getDescription()
    {
        return 'Curated Lists allow users to build lists of events and share them with others.';
    }
}
