<?php

namespace userpermissions;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class CalendarAdministrateUserPermission extends \BaseUserPermission
{
    public function getUserPermissionKey()
    {
        return 'CALENDAR_ADMINISTRATE';
    }

    public function isForSite()
    {
        return true;
    }

    public function requiresUser()
    {
        return true;
    }

    public function requiresEditorUser()
    {
        return true;
    }
}
