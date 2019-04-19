<?php


use models\UserAccountModel;
use models\SiteModel;
use repositories\UserNotificationPreferenceRepository;

/**
 *
 * Each User Notification has a seperate type. Types should be represented by classes that extend this.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseUserNotificationType
{
    abstract public function getNewNotification(UserAccountModel $user, SiteModel $site=null);
    
    abstract public function getNotificationFromData($data, UserAccountModel $user=null, SiteModel $site=null);
    
    public function getUserNotificationPreferenceExtensionID()
    {
        return 'org.openacalendar';
    }
    abstract public function getUserNotificationPreferenceType();

    public function getEmailPreference(UserAccountModel $user)
    {
        global $app;
        $repo = new UserNotificationPreferenceRepository($app);
        $pref = $repo->load($user, $this->getUserNotificationPreferenceExtensionID(), $this->getUserNotificationPreferenceType());
        return $pref->getIsEmail();
    }
}
