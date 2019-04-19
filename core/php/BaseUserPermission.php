<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseUserPermission
{
    public function getUserPermissionExtensionID()
    {
        return 'org.openacalendar';
    }

    abstract public function getUserPermissionKey();


    public function isForIndex()
    {
        return false;
    }

    public function isForSite()
    {
        return false;
    }

    public function requiresUser()
    {
        return false;
    }

    public function requiresVerifiedUser()
    {
        return false;
    }

    public function requiresEditorUser()
    {
        return false;
    }


    /**
     *
     * If a user has a parent permission they are deemed to have the child permission to.
     * EG a user with the CALENDAR_ADMINISTRATE permission also has the CALENDAR_CHANGE permission.
     *
     * @return array of ("ext id","permission key")
     */
    public function getParentPermissionsIDs()
    {
        return array();
    }
}
