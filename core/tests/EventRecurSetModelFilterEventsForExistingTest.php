<?php


use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use models\EventRecurSetModel;
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
class EventRecurSetModelFilterEventsForExistingTest extends \BaseAppWithDBTest
{
    public function mktime($year=2012, $month=1, $day=1, $hour=0, $minute=0, $second=0)
    {
        $dt = new \DateTime('', new \DateTimeZone('UTC'));
        $dt->setTime($hour, $minute, $second);
        $dt->setDate($year, $month, $day);
        return $dt;
    }
    
    public function testExists1()
    {
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
        
        $event1 = new EventModel();
        $event1->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event1->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event1, $site, $user, $group);
        
        $event2 = new EventModel();
        $event2->setStartAt($this->mktime(2013, 8, 2, 19, 0, 0));
        $event2->setEndAt($this->mktime(2013, 8, 2, 21, 0, 0));

        $eventRepository->create($event2, $site, $user, $group);

        $eventProposed = new EventModel();
        $eventProposed->setStartAt($this->mktime(2013, 8, 2, 19, 0, 0));
        $eventProposed->setEndAt($this->mktime(2013, 8, 2, 21, 0, 0));
        
        $ersm = new EventRecurSetModel();
        $event1 = $eventRepository->loadBySlug($site, $event1->getSlug());
        $events = $ersm->filterEventsForExisting($event1, array($eventProposed));
        
        $this->assertEquals(0, count($events));
    }
    
    public function testExistsInDifferentGroup1()
    {
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
        
        $event1 = new EventModel();
        $event1->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event1->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event1, $site, $user, $group);
        
        // this event is not in the same group as event1 so it won't count as a duplicate
        $event2 = new EventModel();
        $event2->setStartAt($this->mktime(2013, 8, 2, 19, 0, 0));
        $event2->setEndAt($this->mktime(2013, 8, 2, 21, 0, 0));

        $eventRepository->create($event2, $site, $user);

        $eventProposed = new EventModel();
        $eventProposed->setGroup($group);
        $eventProposed->setStartAt($this->mktime(2013, 8, 2, 19, 0, 0));
        $eventProposed->setEndAt($this->mktime(2013, 8, 2, 21, 0, 0));
        
        $ersm = new EventRecurSetModel();
        $events = $ersm->filterEventsForExisting($event1, array($eventProposed));
        
        $this->assertEquals(1, count($events));
    }
    
    public function testNotExists1()
    {
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
        
        $event1 = new EventModel();
        $event1->setGroup($group);
        $event1->setStartAt($this->mktime(2013, 8, 1, 19, 0, 0));
        $event1->setEndAt($this->mktime(2013, 8, 1, 21, 0, 0));

        $eventRepository = new EventRepository($this->app);
        $eventRepository->create($event1, $site, $user);
        
        
        $eventProposed = new EventModel();
        $eventProposed->setGroup($group);
        $eventProposed->setStartAt($this->mktime(2013, 8, 2, 19, 0, 0));
        $eventProposed->setEndAt($this->mktime(2013, 8, 2, 21, 0, 0));
        
        
        $ersm = new EventRecurSetModel();
        $events = $ersm->filterEventsForExisting($event1, array($eventProposed));
        
        $this->assertEquals(1, count($events));
    }
}
