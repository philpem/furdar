<?php

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use models\VenueModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\EventRepository;
use repositories\UserHasNoEditorPermissionsInSiteRepository;
use repositories\VenueRepository;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserHasNoEditorPermissionsInSiteTest extends \BaseAppWithDBTest
{
    public function testAddAndRemove()
    {
        $this->addCountriesToTestDB();

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);


        $siteModel = new \models\SiteModel();
        $siteModel->setTitle("Test");
        $siteModel->setSlug("test");

        $siteRepository = new \repositories\SiteRepository($this->app);
        $countryRepository = new \repositories\CountryRepository($this->app);
        $siteRepository->create($siteModel, $user, array($countryRepository->loadByTwoCharCode("GB")), $this->getSiteQuotaUsedForTesting(), true);

        // ########################################## Not there

        $userHasNoEditorPermissionsInSiteRepo = new UserHasNoEditorPermissionsInSiteRepository($this->app);

        $this->assertFalse($userHasNoEditorPermissionsInSiteRepo->isUserInSite($user, $siteModel));

        $userAccountRepoBuilder = new \repositories\builders\UserAccountRepositoryBuilder($this->app);
        $userAccountRepoBuilder->setUserHasNoEditorPermissionsInSite($siteModel);
        $this->assertEquals(0, count($userAccountRepoBuilder->fetchAll()));

        // ########################################## Add

        $userHasNoEditorPermissionsInSiteRepo->addUserToSite($user, $siteModel);


        // ########################################## There

        $this->assertTrue($userHasNoEditorPermissionsInSiteRepo->isUserInSite($user, $siteModel));


        $userAccountRepoBuilder = new \repositories\builders\UserAccountRepositoryBuilder($this->app);
        $userAccountRepoBuilder->setUserHasNoEditorPermissionsInSite($siteModel);
        $this->assertEquals(1, count($userAccountRepoBuilder->fetchAll()));

        // ########################################## Remove

        $userHasNoEditorPermissionsInSiteRepo->removeUserFromSite($user, $siteModel);

        // ########################################## There

        $this->assertFalse($userHasNoEditorPermissionsInSiteRepo->isUserInSite($user, $siteModel));

        $userAccountRepoBuilder = new \repositories\builders\UserAccountRepositoryBuilder($this->app);
        $userAccountRepoBuilder->setUserHasNoEditorPermissionsInSite($siteModel);
        $this->assertEquals(0, count($userAccountRepoBuilder->fetchAll()));
    }
}
