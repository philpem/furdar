<?php


namespace usernotifications\preferences;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class WatchPromptNotificationPreference extends \BaseUserNotificationPreference
{
    public function getLabel()
    {
        return 'Send emails when something I watch is running out of future events';
    }

    public function getUserNotificationPreferenceType()
    {
        return 'WatchPrompt';
    }

    public function isAboutEventsInterestedIn(): bool
    {
        return false;
    }
    public function isAboutEditsIn(): bool
    {
        return true;
    }
}
