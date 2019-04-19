<?php


namespace sitefeatures;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EditCommentsFeature extends \BaseSiteFeature
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
        return 'EditComments';
    }


    public function getTitle()
    {
        return 'Edit Comments';
    }

    public function getDescription()
    {
        return 'On every edit, the user can optionaly put a comment that will appear in the history.';
    }
}
