<?php


namespace usernotifications\models;

use models\UserAccountModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserRequestsAccessNotifyAdminNotificationModel extends \BaseUserNotificationModel
{
    public function __construct()
    {
        $this->from_extension_id = 'org.openacalendar';
        $this->from_user_notification_type = 'UserRequestsAccessNotifyAdmin';
    }

    public function setRequestingUser(UserAccountModel $user)
    {
        $this->data['user'] = $user->getId();
    }
    
    public function getNotificationText()
    {
        return "A user has requested access";
    }
    
    public function getNotificationURL()
    {
        global $CONFIG;
        return $CONFIG->getWebSiteDomainSecure($this->site->getSlug()).'/admin/users';
    }
}
