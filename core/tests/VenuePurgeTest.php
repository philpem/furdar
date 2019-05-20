<?php

use models\UserAccountModel;
use models\SiteModel;
use models\VenueModel;
use models\AreaModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\VenueRepository;
use repositories\EventRepository;
use repositories\CountryRepository;
use repositories\builders\VenueRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenuePurgeTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $this->addCountriesToTestDB();

        $this->app['timesource']->mock(2014, 10, 1, 1, 1, 0);
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
        $siteRepo->create($site, $user, array(), $this->getSiteQuotaUsedForTesting());
        
        $countryRepo = new CountryRepository($this->app);
        $gb = $countryRepo->loadByTwoCharCode('GB');

        $area = new AreaModel();
        $area->setTitle("test");
        $area->setDescription("test test");

        $areaRepo = new \repositories\AreaRepository($this->app);
        $areaRepo->create($area, null, $site, $gb, $user);


        $venue = new VenueModel();
        $venue->setTitle("test");
        $venue->setDescription("test test");
        $venue->setCountryId($gb->getId());
        $venue->setAreaId($area->getId());

        $venueRepo = new VenueRepository($this->app);
        $venueRepo->create($venue, $site, $user);

        $venueDuplicate = new VenueModel();
        $venueDuplicate->setTitle("test Duplicate");

        $venueRepo->create($venueDuplicate, $site, $user);
        $this->app['timesource']->mock(2014, 10, 1, 1, 2, 0);
        $venueRepo->markDuplicate($venueDuplicate, $venue, $user);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0, 'Europe/London'));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0, 'Europe/London'));
        $event->setVenueId($venue->getId());

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);

        $sysadminCommentRepo = new \repositories\SysAdminCommentRepository($this->app);
        $sysadminCommentRepo->createAboutVenue($venue, "TEST", null);

        ## Test
        $this->assertNotNull($venueRepo->loadBySlug($site, $venue->getSlug()));
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($venue->getId(), $event->getVenueId());

        ## Now Purge!
        $venueRepo->purge($venue);

        ## Test
        $this->assertNull($venueRepo->loadBySlug($site, $venue->getSlug()));
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertNull($event->getVenueId());
    }
}
