<?php

use models\GroupHistoryModel;
use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\GroupHistoryRepository;
use \repositories\builders\HistoryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupHistoryWithDBTest extends \BaseAppWithDBTest
{
    public function testIntegration1()
    {
        $this->app['timesource']->mock(2014, 1, 1, 12, 0, 0);
        
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
        
        ## Create group
        $this->app['timesource']->mock(2014, 1, 1, 13, 0, 0);
        $group = new GroupModel();
        $group->setTitle("test");
        $group->setDescription("test test");
        $group->setUrl("http://www.group.com");
        
        $groupRepo = new GroupRepository($this->app);
        $groupRepo->create($group, $site, $user);
        
        ## Edit group
        $this->app['timesource']->mock(2014, 1, 1, 14, 0, 0);
        
        $group = $groupRepo->loadById($group->getId());
        $group->setTwitterUsername("testy");
        $groupRepo->edit($group, $user);
        
        ## Now save changed flags on these .....
        $groupHistoryRepo = new GroupHistoryRepository($this->app);
        $stat = $this->app['db']->prepare("SELECT * FROM group_history");
        $stat->execute();
        while ($data = $stat->fetch()) {
            $groupHistory = new GroupHistoryModel();
            $groupHistory->setFromDataBaseRow($data);
            $groupHistoryRepo->ensureChangedFlagsAreSet($groupHistory);
        }
        
        ## Now load and check
        $historyRepo = new HistoryRepositoryBuilder($this->app);
        $historyRepo->setGroup($group);
        $historyRepo->setIncludeEventHistory(false);
        $historyRepo->setIncludeVenueHistory(false);
        $historyRepo->setIncludeGroupHistory(true);
        $histories = $historyRepo->fetchAll();
        
        $this->assertEquals(2, count($histories));
        
        #the edit
        $this->assertEquals(false, $histories[0]->getTitleChanged());
        $this->assertEquals(false, $histories[0]->getDescriptionChanged());
        $this->assertEquals(false, $histories[0]->getUrlChanged());
        $this->assertEquals(true, $histories[0]->getTwitterUsernameChanged());
        $this->assertEquals(false, $histories[0]->getIsDeletedChanged());
                
        #the create
        $this->assertEquals(true, $histories[1]->getTitleChanged());
        $this->assertEquals(true, $histories[1]->getDescriptionChanged());
        $this->assertEquals(true, $histories[1]->getUrlChanged());
        $this->assertEquals(false, $histories[1]->getTwitterUsernameChanged());
        $this->assertEquals(false, $histories[1]->getIsDeletedChanged());
    }
}
