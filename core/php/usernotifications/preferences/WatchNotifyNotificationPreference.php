<?php


namespace usernotifications\preferences;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class WatchNotifyNotificationPreference extends \BaseUserNotificationPreference
{
    public function getLabel()
    {
        return 'Send emails when something I watch changes';
    }

    public function getUserNotificationPreferenceType()
    {
        return 'WatchNotify';
    }

    public function isAboutEventsInterestedIn(): bool
    {
        return true;
    }
    public function isAboutEditsIn(): bool
    {
        return false;
    }
}
