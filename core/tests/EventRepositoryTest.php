<?php

use models\UserAccountModel;
use models\SiteModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\CountryRepository;
use repositories\EventRepository;
use repositories\EventHistoryRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */




class EventRepositoryTest extends \BaseAppWithDBTest
{
    public function testLoadEventJustBeforeEdit()
    {
        $this->app['timesource']->mock(2014, 1, 1, 1, 1, 1);


        $this->addCountriesToTestDB();
        
        $countryRepo = new CountryRepository($this->app);
        $userRepo = new UserAccountRepository($this->app);
        $siteRepo = new SiteRepository($this->app);
        $eventRepo = new EventRepository($this->app);
        $eventHistoryRepo = new EventHistoryRepository($this->app);
        
        #### Setup
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo->create($user);
        
        $site = new SiteModel();
        $site->setTitle("Test");
        $site->setSlug("test");
        
        $siteRepo->create($site, $user, array( $countryRepo->loadByTwoCharCode('GB') ), $this->getSiteQuotaUsedForTesting());
        
        #### Create Event
    
        $this->app['timesource']->mock(2014, 1, 1, 1, 2, 1);
        
        $event = new EventModel();
        $event->setSummary("Cats");
        $event->setDescription("Go Miaow");
        $event->setStartAt(getUTCDateTime(2014, 1, 10, 9, 0, 0));
        $event->setEndAt(getUTCDateTime(2014, 1, 10, 17, 0, 0));
        
        $eventRepo->create($event, $site, $user);
        
        #### Edit Event
        
        $this->app['timesource']->mock(2014, 1, 1, 1, 3, 1);
        
        $event = $eventRepo->loadBySlug($site, $event->getSlug());
        $event->setSummary("Lizards");
        $event->setDescription("Go ?");
        $eventRepo->edit($event, $user);
        #### Edit Event
        
        $this->app['timesource']->mock(2014, 1, 1, 1, 4, 1);
        
        $event = $eventRepo->loadBySlug($site, $event->getSlug());
        $event->setSummary("Dogs");
        $event->setDescription("Go Woof");
        $eventRepo->edit($event, $user);

        #### test: Load Current State
        
        $this->app['timesource']->mock(2014, 1, 1, 1, 5, 1);
        
        $event = $eventRepo->loadBySlug($site, $event->getSlug());
        $this->assertEquals("Dogs", $event->getSummary());
        $this->assertEquals("Go Woof", $event->getDescription());

        #### test: load state before last edit
        
        $this->app['timesource']->mock(2014, 1, 1, 1, 6, 1);
        
        $history = $eventHistoryRepo->loadByEventAndlastEditByUser($event, $user);
        $event = $eventRepo->loadEventJustBeforeEdit($event, $history);
        $this->assertEquals("Lizards", $event->getSummary());
        $this->assertEquals("Go ?", $event->getDescription());
    }
}
