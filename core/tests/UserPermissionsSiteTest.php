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
class UserPermissionsSiteTest extends \BaseAppWithDBTest
{
    public function testSiteOwnerAllEdit()
    {
        $this->app['config']->newUsersAreEditors = true;
        $this->addCountriesToTestDB();

        $userOwner = new UserAccountModel();
        $userOwner->setEmail("test@jarofgreen.co.uk");
        $userOwner->setUsername("test");
        $userOwner->setPassword("password");

        $userVerified = new UserAccountModel();
        $userVerified->setEmail("verified@jarofgreen.co.uk");
        $userVerified->setUsername("verified");
        $userVerified->setPassword("password");

        $userUnverified = new UserAccountModel();
        $userUnverified->setEmail("unverified@jarofgreen.co.uk");
        $userUnverified->setUsername("unverified");
        $userUnverified->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($userOwner);
        $userRepo->verifyEmail($userOwner);
        $userRepo->create($userVerified);
        $userRepo->verifyEmail($userVerified);
        $userRepo->create($userUnverified);

        // reload user object so all flags set correctly
        $userOwner = $userRepo->loadByUserName($userOwner->getUsername());
        $userVerified = $userRepo->loadByUserName($userVerified->getUsername());
        $userUnverified = $userRepo->loadByUserName($userUnverified->getUsername());

        $extensionsManager = new ExtensionManager($this->app);
        $userPerRepo = new \repositories\UserPermissionsRepository($this->app);

        $siteModel = new \models\SiteModel();
        $siteModel->setTitle("Test");
        $siteModel->setSlug("test");

        $siteRepository = new \repositories\SiteRepository($this->app);
        $countryRepository = new \repositories\CountryRepository($this->app);
        $siteRepository->create($siteModel, $userOwner, array($countryRepository->loadByTwoCharCode("GB")), $this->getSiteQuotaUsedForTesting(), true);

        ## Check!

        $extensionsManager = new ExtensionManager($this->app);
        $userPerRepo = new \repositories\UserPermissionsRepository($this->app);

        $permissions = $userPerRepo->getPermissionsForUserInSite($userOwner, $siteModel, false);
        $this->assertEquals(2, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userOwner, $siteModel, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userVerified, $siteModel, false);
        $this->assertEquals(1, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userVerified, $siteModel, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userUnverified, $siteModel, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userUnverified, $siteModel, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForAnonymousInSite($siteModel, false, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForAnyUserInSite($siteModel, false, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForAnyVerifiedUserInSite($siteModel, false, false);
        $this->assertEquals(1, count($permissions->getPermissions()));
    }

    public function testSiteOwnerSpecificEdit()
    {
        $this->app['config']->newUsersAreEditors = true;
        $this->addCountriesToTestDB();

        $userOwner = new UserAccountModel();
        $userOwner->setEmail("test@jarofgreen.co.uk");
        $userOwner->setUsername("test");
        $userOwner->setPassword("password");

        $userVerified = new UserAccountModel();
        $userVerified->setEmail("verified@jarofgreen.co.uk");
        $userVerified->setUsername("verified");
        $userVerified->setPassword("password");

        $userUnverified = new UserAccountModel();
        $userUnverified->setEmail("unverified@jarofgreen.co.uk");
        $userUnverified->setUsername("unverified");
        $userUnverified->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($userOwner);
        $userRepo->verifyEmail($userOwner);
        $userRepo->create($userVerified);
        $userRepo->verifyEmail($userVerified);
        $userRepo->create($userUnverified);

        // reload user object so all flags set correctly
        $userOwner = $userRepo->loadByUserName($userOwner->getUsername());
        $userVerified = $userRepo->loadByUserName($userVerified->getUsername());
        $userUnverified = $userRepo->loadByUserName($userUnverified->getUsername());

        $extensionsManager = new ExtensionManager($this->app);
        $userPerRepo = new \repositories\UserPermissionsRepository($this->app);

        $siteModel = new \models\SiteModel();
        $siteModel->setTitle("Test");
        $siteModel->setSlug("test");

        $siteRepository = new \repositories\SiteRepository($this->app);
        $countryRepository = new \repositories\CountryRepository($this->app);
        $siteRepository->create($siteModel, $userOwner, array($countryRepository->loadByTwoCharCode("GB")), $this->getSiteQuotaUsedForTesting(), false);

        ## Check!

        $extensionsManager = new ExtensionManager($this->app);
        $userPerRepo = new \repositories\UserPermissionsRepository($this->app);

        $permissions = $userPerRepo->getPermissionsForUserInSite($userOwner, $siteModel, false);
        $this->assertEquals(2, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userOwner, $siteModel, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userVerified, $siteModel, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userVerified, $siteModel, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userUnverified, $siteModel, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForUserInSite($userUnverified, $siteModel, true);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForAnonymousInSite($siteModel, false, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForAnyUserInSite($siteModel, false, false);
        $this->assertEquals(0, count($permissions->getPermissions()));

        $permissions = $userPerRepo->getPermissionsForAnyVerifiedUserInSite($siteModel, false, false);
        $this->assertEquals(0, count($permissions->getPermissions()));
    }
}
