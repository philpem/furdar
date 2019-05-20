<?php

use models\UserAccountModel;
use repositories\UserAccountRepository;
use repositories\UserAccountResetRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserResetTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
    
        $userAccountResetRepository = new UserAccountResetRepository($this->app);
        
        # Test1: recently unused is null
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 1);
        $x = $userAccountResetRepository->loadRecentlyUnusedSentForUserAccountId($user->getId(), 180);
        $this->assertNull($x);
        
        # Test2: Request one
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 2);
        $userAccountReset = $userAccountResetRepository->create($user);
        
        #Test 3: recently unused has one
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 3);
        $x = $userAccountResetRepository->loadRecentlyUnusedSentForUserAccountId($user->getId(), 180);
        $this->assertTrue($x != null);
        
        #Test 4: use it
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 4);
        $userRepo->resetAccount($user, $userAccountReset);
        
        # Test5: recently unused is null
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 5);
        $x = $userAccountResetRepository->loadRecentlyUnusedSentForUserAccountId($user->getId(), 180);
        $this->assertNull($x);
    }
    
    
    public function test2()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
    
        $userAccountResetRepository = new UserAccountResetRepository($this->app);
        
        # Test1: recently unused is null
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 1);
        $x = $userAccountResetRepository->loadRecentlyUnusedSentForUserAccountId($user->getId(), 180);
        $this->assertNull($x);
        
        # Test2: Request one
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 2);
        $userAccountReset = $userAccountResetRepository->create($user);
        
        #Test 3: recently unused has one
        $this->app['timesource']->mock(2013, 1, 1, 1, 0, 3);
        $x = $userAccountResetRepository->loadRecentlyUnusedSentForUserAccountId($user->getId(), 180);
        $this->assertTrue($x != null);
        
        # Test4: days pass. recently unused is null again
        $this->app['timesource']->mock(2013, 1, 5, 1, 0, 5);
        $x = $userAccountResetRepository->loadRecentlyUnusedSentForUserAccountId($user->getId(), 180);
        $this->assertNull($x);
    }
}
