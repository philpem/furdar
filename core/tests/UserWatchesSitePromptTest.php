<?php

use models\UserAccountModel;
use models\SiteModel;
use models\EventModel;
use models\UserWatchesSiteModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\UserWatchesSiteRepository;
use repositories\EventRepository;
use repositories\builders\UserWatchesSiteRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSitePromptTest extends \BaseAppWithDBTest
{

    
    /**
     * No events. Don't send email.
     */
    public function test1()
    {
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;

    
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

        $eventRepo = new EventRepository($this->app);
        
        // User will watch site automatically in site->create()
        
        # Test
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertFalse($data['moreEventsNeeded']);
    }
    
    /**
     * One event, months ago. Def send email.
     */
    public function test2()
    {
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;

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
        $start = $this->app['timesource']->getDateTime();
        $start->setDate(2013, 5, 1);
        $start->setTime(0, 0, 0);
        $event->setStartAt($start);
        $end = $this->app['timesource']->getDateTime();
        $end->setDate(2013, 5, 1);
        $end->setTime(1, 0, 0);
        $event->setEndAt($end);
        
        $eventRepo = new EventRepository($this->app);
        $eventRepo->create($event, $site, $user);
        
        // User will watch site automatically in site->create()
                
        # Test
        $this->app['timesource']->mock(2013, 9, 1, 0, 0, 0);
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertTrue($data['moreEventsNeeded']);
    }
    
    /**
     * One event, months in future. Don't Send email.
     */
    public function test3()
    {
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;

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
        $start = $this->app['timesource']->getDateTime();
        $start->setDate(2013, 12, 1);
        $start->setTime(0, 0, 0);
        $event->setStartAt($start);
        $end = $this->app['timesource']->getDateTime();
        $end->setDate(2013, 12, 1);
        $end->setTime(1, 0, 0);
        $event->setEndAt($end);
        
        $eventRepo = new EventRepository($this->app);
        $eventRepo->create($event, $site, $user);
        
        // User will watch site automatically in site->create()
                
        # Test
        $this->app['timesource']->mock(2013, 6, 1, 0, 0, 0);
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertFalse($data['moreEventsNeeded']);
    }
    
    /**
     * One event, week from now, send email.
     */
    public function test4()
    {
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;

    
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
        $start = $this->app['timesource']->getDateTime();
        $start->setDate(2013, 6, 7);
        $start->setTime(0, 0, 0);
        $event->setStartAt($start);
        $end = $this->app['timesource']->getDateTime();
        $end->setDate(2013, 6, 7);
        $end->setTime(1, 0, 0);
        $event->setEndAt($end);
        
        $eventRepo = new EventRepository($this->app);
        $eventRepo->create($event, $site, $user);
        
        // User will watch site automatically in site->create()
                
        # Test
        $this->app['timesource']->mock(2013, 6, 1, 0, 0, 0);
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertTrue($data['moreEventsNeeded']);
    }
    
    /**
     * One event, week from now, but email sent 29 days ago. Don't send email.
     * This tests $this->app['config']->userWatchesPromptEmailSafeGapDays works.
     */
    public function test4a()
    {
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;
        
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);

    
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
        $start = $this->app['timesource']->getDateTime();
        $start->setDate(2013, 6, 7);
        $start->setTime(0, 0, 0);
        $event->setStartAt($start);
        $end = $this->app['timesource']->getDateTime();
        $end->setDate(2013, 6, 7);
        $end->setTime(1, 0, 0);
        $event->setEndAt($end);
        
        $eventRepo = new EventRepository($this->app);
        $eventRepo->create($event, $site, $user);
        
        // User will watch site automatically in site->create()
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        
        $this->app['timesource']->mock(2013, 5, 2, 0, 0, 0);
        $userWatchesSiteRepo->markPromptEmailSent($userWatchesSite, $this->app['timesource']->getDateTime());
        
        # Test
        $this->app['timesource']->mock(2013, 6, 1, 0, 0, 0);
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertFalse($data['moreEventsNeeded']);
    }
    
    /**
     * One event, week from now, but emailed yesterday, dont send email.
     */
    public function test5()
    {
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;
        
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);

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
        $start = $this->app['timesource']->getDateTime();
        $start->setDate(2013, 6, 7);
        $start->setTime(0, 0, 0);
        $event->setStartAt($start);
        $end = $this->app['timesource']->getDateTime();
        $end->setDate(2013, 6, 7);
        $end->setTime(1, 0, 0);
        $event->setEndAt($end);
        
        $eventRepo = new EventRepository($this->app);
        $eventRepo->create($event, $site, $user);
        
        // User will watch site automatically in site->create()
        
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        
        $this->app['timesource']->mock(2013, 6, 1, 0, 0, 0);
        $userWatchesSiteRepo->markPromptEmailSent($userWatchesSite, $this->app['timesource']->getDateTime());

        # Test
        $this->app['timesource']->mock(2013, 6, 2, 0, 0, 0);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertFalse($data['moreEventsNeeded']);
    }
    
    /**
     * One event, 31 days from now, then 30 days, then 29 days, etc, only 1 email sent
     */
    public function test6()
    {
        $this->app['config']->userWatchesPromptEmailSafeGapDays = 30;
        
        $this->app['timesource']->mock(2013, 1, 1, 0, 0, 0);

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
        $start = $this->app['timesource']->getDateTime();
        $start->setDate(2013, 30, 9);
        $start->setTime(9, 0, 0);
        $event->setStartAt($start);
        $end = $this->app['timesource']->getDateTime();
        $end->setDate(2013, 30, 9);
        $end->setTime(12, 0, 0);
        $event->setEndAt($end);
        
        $eventRepo = new EventRepository($this->app);
        $eventRepo->create($event, $site, $user);
        
        // User will watch site automatically in site->create()
        
        $userWatchesSiteRepo = new UserWatchesSiteRepository($this->app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        
        #Before email sent!
        for ($day = 1; $day <= 29; $day++) {
            $this->app['timesource']->mock(2013, $day, 8, 1, 0, 0);
            $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
            $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
            $this->assertFalse($data['moreEventsNeeded']);
        }

        #Email sent!
        $this->app['timesource']->mock(2013, 30, 8, 1, 0, 0);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertTrue($data['moreEventsNeeded']);
        $userWatchesSiteRepo->markPromptEmailSent($userWatchesSite, $this->app['timesource']->getDateTime());
        
        #After email sent
        $this->app['timesource']->mock(2013, 31, 8, 1, 0, 0);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
        $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
        $this->assertFalse($data['moreEventsNeeded']);
        
        for ($day = 1; $day <= 30; $day++) {
            $this->app['timesource']->mock(2013, $day, 9, 1, 0, 0);
            $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
            $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
            $this->assertFalse($data['moreEventsNeeded']);
        }
        
        for ($day = 1; $day <= 31; $day++) {
            $this->app['timesource']->mock(2013, $day, 10, 1, 0, 0);
            $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
            $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
            $this->assertFalse($data['moreEventsNeeded']);
        }
        
        for ($day = 1; $day <= 30; $day++) {
            $this->app['timesource']->mock(2013, $day, 11, 1, 0, 0);
            $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
            $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
            $this->assertFalse($data['moreEventsNeeded']);
        }
        
        for ($day = 1; $day <= 31; $day++) {
            $this->app['timesource']->mock(2013, $day, 12, 1, 0, 0);
            $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $site);
            $data = $userWatchesSite->getPromptEmailData($site, $eventRepo->loadLastNonDeletedNonImportedByStartTimeInSiteId($site->getId()));
            $this->assertFalse($data['moreEventsNeeded']);
        }
    }
}
