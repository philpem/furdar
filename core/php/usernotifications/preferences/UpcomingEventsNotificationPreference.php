<?php


namespace usernotifications\preferences;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpcomingEventsNotificationPreference extends \BaseUserNotificationPreference
{
    public function getLabel()
    {
        return 'Send Emails of upcoming events';
    }

    public function getUserNotificationPreferenceType()
    {
        return 'UpcomingEvents';
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
