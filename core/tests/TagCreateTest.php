<?php

use models\UserAccountModel;
use models\SiteModel;
use models\TagModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\TagRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagCreateTest extends \BaseAppWithDBTest
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
        
        $tag = new TagModel();
        $tag->setTitle("test");
        $tag->setDescription("test test");
        
        $tagRepo = new TagRepository($this->app);
        $tagRepo->create($tag, $site, $user);
        
        $this->checkTagInTest1($tagRepo->loadById($tag->getId()));
        $this->checkTagInTest1($tagRepo->loadBySlug($site, $tag->getSlug()));
    }
    
    protected function checkTagInTest1(TagModel $tag)
    {
        $this->assertEquals("test test", $tag->getDescription());
        $this->assertEquals("test", $tag->getTitle());
    }
}
