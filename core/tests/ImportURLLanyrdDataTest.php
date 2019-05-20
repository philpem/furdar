<?php


use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\ImportModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\ImportRepository;
use import\ImportRun;
use import\ImportICalHandler;
use repositories\builders\EventRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportURLLanyrdDataTest extends \BaseAppWithDBTest
{

    /**
     *
     * @group import
     */
    public function testBasic()
    {
        $this->app['timesource']->mock(2013, 10, 1, 1, 1, 1);
        $this->app['config']->importAllowEventsSecondsIntoFuture = 7776000; // 90 days
        

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
        
        $importRepository = new ImportRepository($this->app);
        
        $importURL = new ImportModel();
        $importURL->setIsEnabled(true);
        $importURL->setSiteId($site->getId());
        $importURL->setGroupId($group->getId());
        $importURL->setTitle("Test");
        $importURL->setUrl("http://test.com");
        
        $importRepository->create($importURL, $site, $user);
        

        
        // Import
        $importURLRun = new ImportRun($this->app, $importURL, $site);
        $importURLRun->setTemporaryFileStorageForTesting(dirname(__FILE__).'/data/Lanyrd1.ical');
        $importURLRun->setFlag(ImportRun::$FLAG_ADD_UIDS);
        $i = new ImportICalHandler($this->app);
        $i->setImportRun($importURLRun);
        $this->assertTrue($i->canHandle());
        $r =  $i->handle();
        
        $importRunner = new TestsImportRunner($this->app);
        $importRunner->testRunImportedEventsToEvents($importURLRun);

        // Load!
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $events = $erb->fetchAll();
        $this->assertEquals(1, count($events));
        $event = $events[0];
        
        $this->assertEquals("State of the Map Scotland 2013", $event->getSummary());
        $this->assertEquals('2013-10-11 00:00:00', $event->getStartAt()->format('Y-m-d H:i:s'));
        $this->assertEquals('2013-10-14 23:59:59', $event->getEndAt()->format('Y-m-d H:i:s'));
        $this->assertEquals("http://sotms.eventbrite.com/\n\nhttp://lanyrd.com/crkmt", $event->getDescription());
        $this->assertEquals('http://lanyrd.com/2013/sotmscot2013/', $event->getURL());
        $this->assertFalse($event->getIsDeleted());


        // Look for event
        $erb = new EventRepositoryBuilder($this->app);
        $erb->setSite($site);
        $erb->setImport($importURL);
        $this->assertEquals(1, count($erb->fetchAll()));
    }
}
