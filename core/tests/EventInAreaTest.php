<?php

use models\UserAccountModel;
use models\SiteModel;
use models\AreaModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\AreaRepository;
use repositories\EventRepository;
use repositories\CountryRepository;
use repositories\builders\EventRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventInAreaTest extends \BaseAppWithDBTest
{
    public function mktime($year=2012, $month=1, $day=1, $hour=0, $minute=0, $second=0)
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        $dt->setTime($hour, $minute, $second);
        $dt->setDate($year, $month, $day);
        return $dt;
    }
    
    public function testInArea()
    {
        $this->addCountriesToTestDB();
        
        $this->app['timesource']->mock(2013, 7, 1, 7, 0, 0);
        
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
        
        $area1 = new AreaModel();
        $area1->setTitle("scotland");
        
        $area2 = new AreaModel();
        $area2->setTitle("england");
        
        $areaRepo = new AreaRepository($this->app);
        $countryRepo = new CountryRepository($this->app);
        $areaRepo->create($area1, null, $site, $countryRepo->loadByTwoCharCode('GB'), $user);
        $areaRepo->buildCacheAreaHasParent($area1);
        $areaRepo->create($area2, null, $site, $countryRepo->loadByTwoCharCode('GB'), $user);
        $areaRepo->buildCacheAreaHasParent($area2);
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));
        $event->setAreaId($area1->getId());
                
        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);
        
        #test - find in erb
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $erb->setArea($area1);
        $events = $erb->fetchAll();
        
        $this->assertEquals(1, count($events));
        $this->assertEquals($event->getId(), $events[0]->getId());
        
        #test - don't find in erb
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $erb->setArea($area2);
        $events = $erb->fetchAll();
        
        $this->assertEquals(0, count($events));
    }
    
    
    
    public function testInChildArea()
    {
        $this->addCountriesToTestDB();
        
        $this->app['timesource']->mock(2013, 7, 1, 7, 0, 0);
        
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
        
        $area1 = new AreaModel();
        $area1->setTitle("scotland");
        
        $area1child = new AreaModel();
        $area1child->setTitle("edinburgh");
        
        $area2 = new AreaModel();
        $area2->setTitle("england");
        
        $areaRepo = new AreaRepository($this->app);
        $countryRepo = new CountryRepository($this->app);
        $areaRepo->create($area1, null, $site, $countryRepo->loadByTwoCharCode('GB'), $user);
        $areaRepo->buildCacheAreaHasParent($area1);
        $areaRepo->create($area1child, $area1, $site, $countryRepo->loadByTwoCharCode('GB'), $user);
        $areaRepo->buildCacheAreaHasParent($area1child);
        $areaRepo->create($area2, null, $site, $countryRepo->loadByTwoCharCode('GB'), $user);
        $areaRepo->buildCacheAreaHasParent($area2);
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));
        $event->setAreaId($area1child->getId());
                
        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);
        
        #test - find in erb
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $erb->setArea($area1);
        $events = $erb->fetchAll();
        
        $this->assertEquals(1, count($events));
        $this->assertEquals($event->getId(), $events[0]->getId());
        
        
        #test - find in erb
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $erb->setArea($area1child);
        $events = $erb->fetchAll();
        
        $this->assertEquals(1, count($events));
        $this->assertEquals($event->getId(), $events[0]->getId());
        
        #test - don't find in erb
        
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $erb->setArea($area2);
        $events = $erb->fetchAll();
        
        $this->assertEquals(0, count($events));
    }
}
