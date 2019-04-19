<?php

use models\UserAccountModel;
use repositories\UserAccountRepository;
use repositories\UserNotificationPreferenceRepository;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserNotificationPreferenceRepositoryTest extends \BaseAppWithDBTest
{
    public function testGetDefault()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
        
        
        $prefRepo = new UserNotificationPreferenceRepository($this->app);
        
        ### Test
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchPrompt');
        $this->assertEquals(false, $pref->getIsEmail());
    }


    public function testSetThenGet()
    {
        $user = new UserAccountModel();
        $user->setEmail("test@jarofgreen.co.uk");
        $user->setUsername("test");
        $user->setPassword("password");
        
        $userRepo = new UserAccountRepository($this->app);
        $userRepo->create($user);
        
        
        $prefRepo = new UserNotificationPreferenceRepository($this->app);
        
        ### Set
        $prefRepo->editEmailPreference($user, 'org.openacalendar', 'WatchPrompt', true);
        
        ### Test
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchPrompt');
        $this->assertEquals(true, $pref->getIsEmail());
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchNotify');
        $this->assertEquals(false, $pref->getIsEmail());
        
        ### Set
        $prefRepo->editEmailPreference($user, 'org.openacalendar', 'WatchPrompt', false);
        
        ### Test
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchPrompt');
        $this->assertEquals(false, $pref->getIsEmail());
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchNotify');
        $this->assertEquals(false, $pref->getIsEmail());
        
        
        ### Set
        $prefRepo->editEmailPreference($user, 'org.openacalendar', 'WatchNotify', true);
        
        ### Test
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchPrompt');
        $this->assertEquals(false, $pref->getIsEmail());
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchNotify');
        $this->assertEquals(true, $pref->getIsEmail());

        
        
        ### Set
        $prefRepo->editEmailPreference($user, 'org.openacalendar', 'WatchPrompt', true);
        
        ### Test
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchPrompt');
        $this->assertEquals(true, $pref->getIsEmail());
        $pref = $prefRepo->load($user, 'org.openacalendar', 'WatchNotify');
        $this->assertEquals(true, $pref->getIsEmail());
    }
}
