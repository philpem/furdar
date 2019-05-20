<?php

use models\EventModel;
use models\GroupModel;
use models\UserAccountModel;
use models\SiteModel;
use models\AreaModel;
use repositories\EventRepository;
use repositories\GroupRepository;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\CountryRepository;
use repositories\AreaRepository;
use repositories\builders\EventRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */




class EventRepositoryBuilderUserTest extends \BaseAppWithDBTest
{
    public function testUserAttendingEventNoWatches()
    {
        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $groupRepo = new GroupRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesGroupRepo = new \repositories\UserWatchesGroupRepository($this->app);
        $userAtEventRepo = new \repositories\UserAtEventRepository($this->app);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userAttending = new UserAccountModel();
        $userAttending->setEmail("test2@jarofgreen.co.uk");
        $userAttending->setUsername("test2");
        $userAttending->setPassword("password1");

        $userRepo->create($userAttending);

        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        // test before



        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, false, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test
        $userAtEvent = $userAtEventRepo->loadByUserAndEventOrInstanciate($userAttending, $event);
        $userAtEvent->setIsPlanAttending(true);
        $userAtEvent->setIsPlanPublic(false);
        $userAtEventRepo->save($userAtEvent);


        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, false, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }


    public function testUserAttendingEventPublicallyNoWatches()
    {
        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $groupRepo = new GroupRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesGroupRepo = new \repositories\UserWatchesGroupRepository($this->app);
        $userAtEventRepo = new \repositories\UserAtEventRepository($this->app);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userAttending = new UserAccountModel();
        $userAttending->setEmail("test2@jarofgreen.co.uk");
        $userAttending->setUsername("test2");
        $userAttending->setPassword("password1");

        $userRepo->create($userAttending);

        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        // test before
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, false, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test
        $userAtEvent = $userAtEventRepo->loadByUserAndEventOrInstanciate($userAttending, $event);
        $userAtEvent->setIsPlanAttending(true);
        $userAtEvent->setIsPlanPublic(true);
        $userAtEventRepo->save($userAtEvent);


        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userAttending, false, false, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));
    }


    public function testUserWatchingGroupsWithAnEventInTwoGroups()
    {
        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $groupRepo = new GroupRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesGroupRepo = new \repositories\UserWatchesGroupRepository($this->app);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userWatchesMain = new UserAccountModel();
        $userWatchesMain->setEmail("test1@jarofgreen.co.uk");
        $userWatchesMain->setUsername("test1");
        $userWatchesMain->setPassword("password1");

        $userRepo->create($userWatchesMain);

        $userWatchesOther = new UserAccountModel();
        $userWatchesOther->setEmail("test2@jarofgreen.co.uk");
        $userWatchesOther->setUsername("test2");
        $userWatchesOther->setPassword("password1");

        $userRepo->create($userWatchesOther);

        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $groupMain = new GroupModel();
        $groupMain->setTitle("test");

        $groupRepo->create($groupMain, $site, $user);

        $groupOther = new GroupModel();
        $groupOther->setTitle("test");

        $groupRepo->create($groupOther, $site, $user);


        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user, $groupMain, array($groupOther));

        // test before

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));


        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesOther, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesOther, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching main group gets event
        $userWatchesGroupRepo->startUserWatchingGroup($userWatchesMain, $groupMain);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching other group gets event
        $userWatchesGroupRepo->startUserWatchingGroup($userWatchesOther, $groupOther);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesOther, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesOther, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }


    public function testUserWatchingSite()
    {
        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $groupRepo = new GroupRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesSiteRepo = new \repositories\UserWatchesSiteRepository($this->app);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userWatchesMain = new UserAccountModel();
        $userWatchesMain->setEmail("test1@jarofgreen.co.uk");
        $userWatchesMain->setUsername("test1");
        $userWatchesMain->setPassword("password1");

        $userRepo->create($userWatchesMain);


        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());


        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        // test before
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching main group gets event
        $userWatchesSiteRepo->startUserWatchingSite($userWatchesMain, $site);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }

    public function testUserWatchingArea()
    {
        $this->app['timesource']->mock(2014, 01, 01, 9, 0, 0);

        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $areaRepo = new AreaRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $groupRepo = new GroupRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesAreaRepo = new \repositories\UserWatchesAreaRepository($this->app);
        $GB = $countryRepo->loadByTwoCharCode("GB");

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userWatchesMain = new UserAccountModel();
        $userWatchesMain->setEmail("test1@jarofgreen.co.uk");
        $userWatchesMain->setUsername("test1");
        $userWatchesMain->setPassword("password1");

        $userRepo->create($userWatchesMain);


        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $area =  new AreaModel();
        $area->setTitle("Scotland");

        $areaRepo->create($area, null, $site, $GB);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        $event->setAreaId($area->getId());

        $this->app['timesource']->mock(2014, 01, 01, 9, 1, 0);
        $eventRepository->edit($event);

        // test before
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching main group gets event
        $userWatchesAreaRepo->startUserWatchingArea($userWatchesMain, $area);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }

    public function testUserWatchingParentArea()
    {
        $this->app['timesource']->mock(2014, 01, 01, 9, 0, 0);

        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $areaRepo = new AreaRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $groupRepo = new GroupRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesAreaRepo = new \repositories\UserWatchesAreaRepository($this->app);
        $GB = $countryRepo->loadByTwoCharCode("GB");

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userWatchesMain = new UserAccountModel();
        $userWatchesMain->setEmail("test1@jarofgreen.co.uk");
        $userWatchesMain->setUsername("test1");
        $userWatchesMain->setPassword("password1");

        $userRepo->create($userWatchesMain);


        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $area =  new AreaModel();
        $area->setTitle("Scotland");

        $areaRepo->create($area, null, $site, $GB);

        $areaChild =  new AreaModel();
        $areaChild->setTitle("Edinburgh");
        $areaRepo->create($areaChild, $area, $site, $GB);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        $event->setAreaId($areaChild->getId());

        $this->app['timesource']->mock(2014, 01, 01, 9, 1, 0);
        $eventRepository->edit($event);

        // have to update child cache
        $areaRepo->buildCacheAreaHasParent($area);
        $areaRepo->buildCacheAreaHasParent($areaChild);

        // test before
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching main group gets event
        $userWatchesAreaRepo->startUserWatchingArea($userWatchesMain, $area);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }
    public function testUserWatchingAreaWithVenue()
    {
        $this->app['timesource']->mock(2014, 01, 01, 9, 0, 0);

        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $areaRepo = new AreaRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $venueRepo = new \repositories\VenueRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesAreaRepo = new \repositories\UserWatchesAreaRepository($this->app);
        $GB = $countryRepo->loadByTwoCharCode("GB");

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userWatchesMain = new UserAccountModel();
        $userWatchesMain->setEmail("test1@jarofgreen.co.uk");
        $userWatchesMain->setUsername("test1");
        $userWatchesMain->setPassword("password1");

        $userRepo->create($userWatchesMain);


        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $area =  new AreaModel();
        $area->setTitle("Scotland");

        $areaRepo->create($area, null, $site, $GB);

        $venue = new \models\VenueModel();
        $venue->setTitle("Castle");
        $venue->setAreaId($area->getId());

        $venueRepo->create($venue, $site, $user);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        $event->setVenueId($venue->getId());

        $this->app['timesource']->mock(2014, 01, 01, 9, 1, 0);
        $eventRepository->edit($event);

        // test before
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching main group gets event
        $userWatchesAreaRepo->startUserWatchingArea($userWatchesMain, $area);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }

    public function testUserWatchingParentAreaWithVenue()
    {
        $this->app['timesource']->mock(2014, 01, 01, 9, 0, 0);

        $this->addCountriesToTestDB();

        $countryRepo = new CountryRepository($this->app);
        $areaRepo = new AreaRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $venueRepo = new \repositories\VenueRepository($this->app);
        $eventRepository = new EventRepository($this->app);
        $userWatchesAreaRepo = new \repositories\UserWatchesAreaRepository($this->app);
        $GB = $countryRepo->loadByTwoCharCode("GB");

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo->create($user);

        $userWatchesMain = new UserAccountModel();
        $userWatchesMain->setEmail("test1@jarofgreen.co.uk");
        $userWatchesMain->setUsername("test1");
        $userWatchesMain->setPassword("password1");

        $userRepo->create($userWatchesMain);


        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");

        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $area =  new AreaModel();
        $area->setTitle("Scotland");

        $areaRepo->create($area, null, $site, $GB);

        $areaChild =  new AreaModel();
        $areaChild->setTitle("Edinburgh");
        $areaRepo->create($areaChild, $area, $site, $GB);

        $venue = new \models\VenueModel();
        $venue->setTitle("Castle");
        $venue->setAreaId($areaChild->getId());

        $venueRepo->create($venue, $site, $user);


        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 11, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 11, 10, 21, 0, 0));

        $eventRepository->create($event, $site, $user);

        $event->setVenueId($venue->getId());

        $this->app['timesource']->mock(2014, 01, 01, 9, 1, 0);
        $eventRepository->edit($event);

        // have to update child cache
        $areaRepo->buildCacheAreaHasParent($area);
        $areaRepo->buildCacheAreaHasParent($areaChild);

        // test before
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));

        // test watching main group gets event
        $userWatchesAreaRepo->startUserWatchingArea($userWatchesMain, $area);

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, true);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));

        $erb = new EventRepositoryBuilder($this->app);
        $erb->setUserAccount($userWatchesMain, false, true, true, false);
        $events = $erb->fetchAll();
        $this->assertEquals(0, count($events));
    }
}
