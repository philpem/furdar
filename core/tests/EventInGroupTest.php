<?php

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\EventRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventInGroupTest extends \BaseAppWithDBTest
{
    public function mktime($year=2012, $month=1, $day=1, $hour=0, $minute=0, $second=0)
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        $dt->setTime($hour, $minute, $second);
        $dt->setDate($year, $month, $day);
        return $dt;
    }
    
    public function testCreateInGroup()
    {
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
        
        $group = new GroupModel();
        $group->setTitle("test");
        $group->setDescription("test test");
        $group->setUrl("http://www.group.com");
        
        $groupRepo = new GroupRepository($this->app);
        $groupRepo->create($group, $site, $user);
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user, $group);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group->getId(), $event->getGroupId());
        $this->assertEquals("test", $event->getGroupTitle());
    }
    
    
    
    public function testAddRemove()
    {
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
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);
        
        $group1 = new GroupModel();
        $group1->setTitle("test");
        $group1->setDescription("test test");
        $group1->setUrl("http://www.group.com");
        
        $group2 = new GroupModel();
        $group2->setTitle("test2");
        $group2->setDescription("test 2");
        
        $groupRepo = new GroupRepository($this->app);
        $groupRepo->create($group1, $site, $user);
        $groupRepo->create($group2, $site, $user);
        
        ## Add event to group1, test
        $groupRepo->addEventToGroup($event, $group1, $user);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group1->getId(), $event->getGroupId());
        $this->assertEquals("test", $event->getGroupTitle());
        
        ## Add event to group2, test group1 is still main group
        $groupRepo->addEventToGroup($event, $group2, $user);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group1->getId(), $event->getGroupId());
        $this->assertEquals("test", $event->getGroupTitle());
        
        ## remove group1, group2 should become main group
        $groupRepo->removeEventFromGroup($event, $group1, $user);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group2->getId(), $event->getGroupId());
        $this->assertEquals("test2", $event->getGroupTitle());
    }
    
    
    
    public function testAddSet()
    {
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
        
        $event = new EventModel();
        $event->setSummary("test");
        $event->setDescription("test test");
        $event->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event, $site, $user);
        
        $group1 = new GroupModel();
        $group1->setTitle("test");
        $group1->setDescription("test test");
        $group1->setUrl("http://www.group.com");
        
        $group2 = new GroupModel();
        $group2->setTitle("test2");
        $group2->setDescription("test 2");
        
        $groupRepo = new GroupRepository($this->app);
        $groupRepo->create($group1, $site, $user);
        $groupRepo->create($group2, $site, $user);
        
        ## Add event to group1, test
        $groupRepo->addEventToGroup($event, $group1, $user);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group1->getId(), $event->getGroupId());
        $this->assertEquals("test", $event->getGroupTitle());
        
        ## Add event to group2, test group1 is still main group
        $groupRepo->addEventToGroup($event, $group2, $user);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group1->getId(), $event->getGroupId());
        $this->assertEquals("test", $event->getGroupTitle());
        
        ## set main group to group2, test
        $groupRepo->setMainGroupForEvent($group2, $event, $user);
        
        $event = $eventRepository->loadBySlug($site, $event->getSlug());
        $this->assertEquals($group2->getId(), $event->getGroupId());
        $this->assertEquals("test2", $event->getGroupTitle());
    }
}
