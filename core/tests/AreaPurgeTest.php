<?php

use models\UserAccountModel;
use models\SiteModel;
use models\AreaModel;
use models\EventModel;
use models\CountryModelModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\CountryRepository;
use repositories\EventRepository;
use repositories\AreaRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AreaPurgeTest extends BaseAppWithDBTest
{
    public function test1()
    {
        $this->addCountriesToTestDB();
        $countryRepo = new CountryRepository($this->app);
        $areaRepo = new AreaRepository($this->app);

        $this->app['timesource']->mock(2014, 10, 1, 1, 0, 0);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
        
        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");
        
        $siteRepo = new SiteRepository($this->app);
        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());

        $area = new AreaModel();
        $area->setTitle("test");
        $area->setDescription("test test");
        
        $areaRepo->create($area, null, $site, $countryRepo->loadByTwoCharCode('GB'), $user);

        $areaDuplicate = new AreaModel();
        $areaDuplicate->setTitle("test Duplicate");

        $areaRepo->create($areaDuplicate, null, $site, $countryRepo->loadByTwoCharCode('GB'), $user);
        $this->app['timesource']->mock(2014, 10, 1, 2, 0, 0);
        $areaRepo->markDuplicate($areaDuplicate, $area, $user);

        $areaChild = new AreaModel();
        $areaChild->setTitle("test Child");

        $areaRepo->create($areaChild, $area, $site, $countryRepo->loadByTwoCharCode('GB'), $user);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0, 'Europe/London'));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0, 'Europe/London'));
        $event->setAreaId($area->getId());

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);

        $sysadminCommentRepo = new \repositories\SysAdminCommentRepository($this->app);
        $sysadminCommentRepo->createAboutArea($area, "TEST", null);

        ## Test

        $this->assertNotNull($areaRepo->loadById($area->getId()));

        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($area->getId(), $event->getAreaId());


        ## Now Purge!
        $areaRepo->purge($area);


        ## Test
        $this->assertNull($areaRepo->loadById($area->getId()));

        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertNull($event->getAreaId());
    }
}
