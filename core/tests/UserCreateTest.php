<?php

use models\UserAccountModel;
use repositories\UserAccountRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserCreateTest extends \BaseAppWithDBTest
{
    public function test1()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
        
        $this->checkUserInTest1($userRepo->loadByID($user->getId()));
        $this->checkUserInTest1($userRepo->loadByUserName("test"));
        $this->checkUserInTest1($userRepo->loadByEmail("test@jarofgreen.co.uk"));
        $this->checkUserInTest1($userRepo->loadByUserNameOrEmail("test"));
        $this->checkUserInTest1($userRepo->loadByUserNameOrEmail("test@jarofgreen.co.uk"));
    }
    
    protected function checkUserInTest1(UserAccountModel $user)
    {
        $this->assertEquals("test", $user->getUsername());
        $this->assertEquals("test@jarofgreen.co.uk", $user->getEmail());
        $this->assertEquals(false, $user->checkPassword("1234"));
        $this->assertEquals(true, $user->checkPassword("password"));
        $this->assertEquals(false, $user->getIsEmailVerified());
        $this->assertEquals(false, $user->getIsSystemAdmin());
        $this->assertEquals(true, $user->getIsEditor());
    }
}
