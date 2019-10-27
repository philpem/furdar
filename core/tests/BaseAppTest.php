<?php

use models\UserAccountModel;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\GroupRepository;
use repositories\EventRepository;
use repositories\builders\EventRepositoryBuilder;

/** CURRENTLY WE WANT OUR TESTS TO RUN ON UBUNTU 16 (PHPunit 5) AND 18 (PHPunit 6)
 * THIS IS A HACK TO MAKE THEM WORK ON BOTH
 * WHEN WE DON'T CARE ABOUT UBUNTU 16 ANY MORE WE CAN REMOVE IT AND JUST MAKE OUR CLASS EXTEND \PHPUnit\Framework\TestCase DIRECTLY
 **/
if (!class_exists('\PHPUnit_Framework_TestCase'))
{
    class PHPUnit_Framework_TestCase extends \PHPUnit\Framework\TestCase {
    }
}


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class BaseAppTest extends \PHPUnit_Framework_TestCase
{

    /*
     * @var Silex\App
     */
    protected $app;


    protected function setUp()
    {
        global $CONFIG, $DB, $EXTENSIONHOOKRUNNER, $app;

        $CONFIG = new \Config();
        require APP_ROOT_DIR."config.test.php";
        $CONFIG->isDebug = true;


        $this->app = new Silex\Application();
        $app = $this->app;
        $this->app['debug'] = true;
        $this->app['extensions'] = new ExtensionManager($this->app);
        foreach ($CONFIG->extensions as $extensionName) {
            require APP_ROOT_DIR.'/extension/'.$extensionName.'/extension.php';
        }
        $this->app['appconfig'] = new appconfiguration\AppConfigurationManager($DB, $CONFIG);
        $this->app['config'] = $CONFIG;
        $this->app['messagequeproducerhelper'] = function ($app) {
            return new MessageQueProducerHelper($app);
        };
        $this->app['timesource'] = new TimeSource();
        $this->app['userAgent'] = new UserAgent();
        $this->app['extensionhookrunner'] = new ExtensionHookRunner($this->app);

        // These tests don't have a DB. But we put an entry in Pimple for it as some code relies on looking it up.
        // We put null in, so if anf code tries to use it it errors and we can change test to one with DB.
        $this->app['db'] = null;

        $EXTENSIONHOOKRUNNER = new ExtensionHookRunner($this->app);
    }
}
