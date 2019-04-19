<?php

use models\UserAccountModel;
use repositories\UserAccountRepository;
use repositories\UserAccountResetRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserPermissionsIndexConfigTrueTest extends \BaseAppWithDBTest
{
    protected function setConfig(\Config $config)
    {
        $config->canCreateSitesVerifiedEditorUsers = true;
    }

    public function testAllUsersCreateSiteByDefault()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
        $userRepo->verifyEmail($user);

        // reload user object so all flags set correctly
        $user = $userRepo->loadByUserName("test");

        $extensionsManager = new ExtensionManager($this->app);
        $userPerRepo = new \repositories\UserPermissionsRepository($this->app);

        ## user can create sites, anon can't!

        $permissions = $userPerRepo->getPermissionsForUserInIndex(null, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInIndex(null, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInIndex($user, false);
        $this->assertEquals(1, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInIndex($user, true);
        $this->assertEquals(0, count($permissions->getPermissions()));
    }
}
