<?php

namespace usernotifications\models;

use models\GroupModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSitePromptNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'UserWatchesSitePrompt';
    }
    
    public function setGroup(GroupModel $group)
    {
        $this->data['group'] = $group->getId();
    }
    
    public function getNotificationText()
    {
        return "There will soon be no more events in ".$this->site->getTitle();
    }
    
    public function getNotificationURL()
    {
        global $CONFIG;
        return $CONFIG->getWebSiteDomainSecure($this->site->getSlug());
    }
}
