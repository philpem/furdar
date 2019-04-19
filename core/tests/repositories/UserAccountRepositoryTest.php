<?php

namespace tests\repositories;

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\EventRepository;
use repositories\builders\GroupRepositoryBuilder;
use TimeSource;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserAccountRepositoryTest extends \BaseAppWithDBTest
{
    public function testPurge()
    {

        ## CREATE
        $this->app['timesource']->mock(2013, 7, 1, 7, 0, 0);

        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);

        ## Can Purge
        $this->assertTrue($this->app['extensions']->getExtensionById('org.openacalendar')->canPurgeUser($user));

        ## Purge

        $userRepo = new UserAccountRepository($this->app);
        $userRepo->purge($user);
    }
}
