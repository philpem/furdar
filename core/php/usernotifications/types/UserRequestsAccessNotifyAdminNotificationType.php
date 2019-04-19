<?php

namespace usernotifications\types;

use models\UserAccountModel;
use models\SiteModel;
use usernotifications\models\UserRequestsAccessNotifyAdminNotificationModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserRequestsAccessNotifyAdminNotificationType extends \BaseUserNotificationType
{
    public function getNewNotification(UserAccountModel $user, SiteModel $site=null)
    {
        $r =  new UserRequestsAccessNotifyAdminNotificationModel();
        $r->setUserSiteAndIsEmail($user, $site, true);
        return $r;
    }
    
    public function getNotificationFromData($data, UserAccountModel $user=null, SiteModel $site=null)
    {
        $r =  new UserRequestsAccessNotifyAdminNotificationModel();
        $r->setFromDataBaseRow($data);
        $r->setSite($site);
        return $r;
    }
        
    public function getUserNotificationPreferenceType()
    {
        return null;
    }
}
