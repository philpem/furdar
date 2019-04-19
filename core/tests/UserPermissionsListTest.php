<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class UserPermissionsListTest extends \BaseAppTest
{
    public function testAnonymousCantHaveChangePermission()
    {
        $extensionManager = new \ExtensionManager($this->app);

        $extensionCore = new \ExtensionCore($this->app);

        $permission = $extensionCore->getUserPermission("CALENDAR_CHANGE");

        $userPermissionList = new UserPermissionsList($extensionManager, array($permission), null, false, true);

        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_CHANGE"));
        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE"));
    }

    public function testUserCanHaveChangePermission()
    {
        $extensionManager = new \ExtensionManager($this->app);

        $extensionCore = new \ExtensionCore($this->app);

        $permission = $extensionCore->getUserPermission("CALENDAR_CHANGE");

        $user = new \models\UserAccountModel();
        $user->setIsEditor(true);

        $userPermissionList = new UserPermissionsList($extensionManager, array($permission), $user, false, true);

        $this->assertTrue($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_CHANGE"));
        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE"));
    }

    public function testUserCantHaveChangePermissionWhenUserNotEditor()
    {
        $extensionManager = new \ExtensionManager($this->app);

        $extensionCore = new \ExtensionCore($this->app);

        $permission = $extensionCore->getUserPermission("CALENDAR_CHANGE");

        $user = new \models\UserAccountModel();
        $user->setIsEditor(false);

        $userPermissionList = new UserPermissionsList($extensionManager, array($permission), $user, false, true);

        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_CHANGE"));
        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE"));
    }

    public function testUserCantHaveChangePermissionWhenReadOnly()
    {
        $extensionManager = new \ExtensionManager($this->app);

        $extensionCore = new \ExtensionCore($this->app);

        $permission = $extensionCore->getUserPermission("CALENDAR_CHANGE");

        $user = new \models\UserAccountModel();
        $user->setIsEditor(true);

        $userPermissionList = new UserPermissionsList($extensionManager, array($permission), $user, true, true);

        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_CHANGE"));
        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE"));
    }

    public function testUserCanHasChangePermissionWhenHasAdminPermission()
    {
        $extensionManager = new \ExtensionManager($this->app);

        $extensionCore = new \ExtensionCore($this->app);

        $permission = $extensionCore->getUserPermission("CALENDAR_ADMINISTRATE");

        $user = new \models\UserAccountModel();
        $user->setIsEditor(true);

        // With include child permissions

        $userPermissionList = new UserPermissionsList($extensionManager, array($permission), $user, false, true);

        $this->assertTrue($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_CHANGE"));
        $this->assertTrue($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE"));

        // With not includeing child permissions

        $userPermissionList = new UserPermissionsList($extensionManager, array($permission), $user, false, false);

        $this->assertFalse($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_CHANGE"));
        $this->assertTrue($userPermissionList->hasPermission("org.openacalendar", "CALENDAR_ADMINISTRATE"));
    }
}
