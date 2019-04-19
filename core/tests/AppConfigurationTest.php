<?php

use appconfiguration\AppConfigurationManager;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AppConfigurationTest extends \BaseAppWithDBTest
{
    public function testGetDefault()
    {
        $appConfigManager = new AppConfigurationManager($this->app['db'], $this->app['config']);
        $def = new \appconfiguration\AppConfigurationDefinition('core', 'key', 'text', true);
        $this->assertEquals('yaks', $appConfigManager->getValue($def, 'yaks'));
    }
    
    public function testSetGet()
    {
        $appConfigManager = new AppConfigurationManager($this->app['db'], $this->app['config']);
        $def = new \appconfiguration\AppConfigurationDefinition('core', 'key', 'text', true);
        
        $appConfigManager->setValue($def, 'moreyaks');
        $this->assertEquals('moreyaks', $appConfigManager->getValue($def, 'yaks'));
    }
    
    public function testSetUpdateGet()
    {
        $appConfigManager = new AppConfigurationManager($this->app['db'], $this->app['config']);
        $def = new \appconfiguration\AppConfigurationDefinition('core', 'key', 'text', true);
        
        $appConfigManager->setValue($def, 'moreyaks');
        $appConfigManager->setValue($def, 'muchyaks');
        $this->assertEquals('muchyaks', $appConfigManager->getValue($def, 'yaks'));
    }
}
