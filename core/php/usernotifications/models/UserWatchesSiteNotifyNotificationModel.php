<?php


namespace usernotifications\models;

use models\GroupModel;

/**
 *
 *
 * @deprecated Use Type UserWatchesNotify instead!
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSiteNotifyNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'UserWatchesSiteNotify';
    }
    
    public function getNotificationText()
    {
        return "There are changes to ".$this->site->getTitle();
    }
    
    public function getNotificationURL()
    {
        global $CONFIG;
        return $CONFIG->getWebSiteDomainSecure($this->site->getSlug()).'/history';
    }
}
