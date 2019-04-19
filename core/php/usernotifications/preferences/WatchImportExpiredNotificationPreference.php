<?php


namespace usernotifications\preferences;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class WatchImportExpiredNotificationPreference extends \BaseUserNotificationPreference
{
    public function getLabel()
    {
        return 'Send emails when something I watch has an importer that expires';
    }
    
    public function getUserNotificationPreferenceType()
    {
        return 'WatchImportExpired';
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
