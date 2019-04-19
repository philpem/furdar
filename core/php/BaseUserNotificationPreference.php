<?php


use models\UserAccountModel;
use models\SiteModel;

/**
 *
 * Users can choose whether to have user notifications emailed to them.
 *
 * They turn on or off several different categories of notification (a Preference),
 * each category is represented by a class that extends this.
 *
 * This is done seperately from BaseUserNotificationType because several
 * types of notification may be turned on or off by one category or Preference.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseUserNotificationPreference
{
    public function getUserNotificationPreferenceExtensionID()
    {
        return 'org.openacalendar';
    }
    abstract public function getUserNotificationPreferenceType();

    abstract public function getLabel();

    public function isAboutEventsInterestedIn(): bool
    {
        return false;
    }
    public function isAboutEditsIn(): bool
    {
        return false;
    }
}
