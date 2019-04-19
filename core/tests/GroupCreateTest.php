<?php

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\builders\GroupRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupCreateTest extends \BaseAppWithDBTest
{
    public function test1()
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
        
        $this->checkGroupInTest1($groupRepo->loadById($group->getId()));
        $this->checkGroupInTest1($groupRepo->loadBySlug($site, $group->getSlug()));
        
        $grb = new GroupRepositoryBuilder($this->app);
        $grb->setFreeTextsearch('test');
        $this->assertEquals(1, count($grb->fetchAll()));
        
        $grb = new GroupRepositoryBuilder($this->app);
        $grb->setFreeTextsearch('cats');
        $this->assertEquals(0, count($grb->fetchAll()));
    }
    
    protected function checkGroupInTest1(GroupModel $group)
    {
        $this->assertEquals("test test", $group->getDescription());
        $this->assertEquals("test", $group->getTitle());
        $this->assertEquals("http://www.group.com", $group->getUrl());
    }
}
