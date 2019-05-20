<?php

use models\UserAccountModel;
use models\SiteModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use models\EventModel;
use repositories\EventRepository;
use repositories\builders\EventRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteCloseTest extends \BaseAppWithDBTest
{
    public function testEventsVanish()
    {

        ## User, Site, Event
        $this->app['timesource']->mock(2014, 1, 1, 1, 2, 3);
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
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt(getUTCDateTime(2014, 5, 10, 19, 0, 0, 'Europe/London'));
        $event->setEndAt(getUTCDateTime(2014, 5, 10, 21, 0, 0, 'Europe/London'));
        $event->setUrl("http://www.info.com");
        $event->setTicketUrl("http://www.tickets.com");

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);
    
        ## Event can be found
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setIncludeEventsFromClosedSites(true);
        $erb->fetchAll();
        $this->assertEquals(1, count($erb->fetchAll()));
        
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setIncludeEventsFromClosedSites(false);
        $erb->fetchAll();
        $this->assertEquals(1, count($erb->fetchAll()));
        
        ## Close Site
        $this->app['timesource']->mock(2014, 2, 1, 1, 2, 3);
        $site->setIsClosedBySysAdmin(true);
        $site->setClosedBySysAdminreason('Testing');
        $siteRepo->edit($site, $user);
        
        ## Event can not be found
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setIncludeEventsFromClosedSites(true);
        $erb->fetchAll();
        $this->assertEquals(1, count($erb->fetchAll()));
        
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setIncludeEventsFromClosedSites(false);
        $erb->fetchAll();
        $this->assertEquals(0, count($erb->fetchAll()));
    }
}
