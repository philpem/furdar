<?php

use models\UserAccountModel;
use models\SiteModel;
use models\VenueModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\VenueRepository;
use repositories\CountryRepository;
use repositories\EventRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueDuplicateTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $this->app['timesource']->mock(2014, 1, 1, 0, 0, 0);
        $this->addCountriesToTestDB();

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

        $venue1 = new VenueModel();
        $venue1->setTitle("test");
        $venue1->setDescription("test test");
        $venue1->setCountryId($gb->getId());

        $venue2 = new VenueModel();
        $venue2->setTitle("test this looks similar");
        $venue2->setDescription("test test");
        $venue2->setCountryId($gb->getId());

        $venueRepo = new VenueRepository($this->app);
        $venueRepo->create($venue1, $site, $user);
        $venueRepo->create($venue2, $site, $user);

        $event = new EventModel();
        $event->setSummary("test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0));
        $event->setVenueId($venue2->getId());

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);

        // Test before
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($venue2->getId(), $event->getVenueId());

        $venue2 = $venueRepo->loadById($venue2->getId());
        $this->assertFalse($venue2->getIsDeleted());
        $this->assertNull($venue2->getIsDuplicateOfId());

        // Mark
        $this->app['timesource']->mock(2014, 1, 1, 2, 0, 0);
        $venueRepo->markDuplicate($venue2, $venue1, $user);

        // Test Duplicate
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($venue1->getId(), $event->getVenueId());

        $venue2 = $venueRepo->loadById($venue2->getId());
        $this->assertTrue($venue2->getIsDeleted());
        $this->assertEquals($venue1->getId(), $venue2->getIsDuplicateOfId());
    }
}
