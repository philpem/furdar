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
    throw new exception('CRAT');
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
class BaseAppWithDBTest extends \PHPUnit_Framework_TestCase
{


    /*
     * @var Silex\App
     */
    protected $app;


    protected function setConfig(\Config $config)
    {
    }

    protected function setUp()
    {
        global $CONFIG, $DB, $EXTENSIONHOOKRUNNER, $app;


        $CONFIG = new \Config();
        require APP_ROOT_DIR."config.test.php";
        $CONFIG->isDebug = true;
        $this->setConfig($CONFIG);

        /** @var PDO * */
        if (!$DB) {
            $DB = new PDO('pgsql:host=' . $CONFIG->databaseHost . ';dbname=' . $CONFIG->databaseName, $CONFIG->databaseUser, $CONFIG->databasePassword);
            $DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $DB->exec("SET CLIENT_ENCODING TO 'utf8'");
            $DB->exec("SET NAMES 'utf8'");
        }

        $this->app = new Silex\Application();
        $app = $this->app;
        $this->app['debug'] = true;
        $this->app['extensions'] = new ExtensionManager($this->app);
        foreach ($CONFIG->extensions as $extensionName) {
            require APP_ROOT_DIR.'/extension/'.$extensionName.'/extension.php';
        }
        $this->app['appconfig'] = new appconfiguration\AppConfigurationManager($DB, $CONFIG);
        $this->app['config'] = $CONFIG;
        $this->app['db'] = $DB;
        $this->app['timesource'] = new TimeSource();
        $this->app['messagequeproducerhelper'] = function ($app) {
            return new MessageQueProducerHelper($app);
        };
        $this->app['userAgent'] = new UserAgent();
        $this->app['extensionhookrunner'] = new ExtensionHookRunner($this->app);

        $EXTENSIONHOOKRUNNER = new ExtensionHookRunner($this->app);

        foreach ($CONFIG->extensions as $extensionName) {
            $file = APP_ROOT_DIR . DIRECTORY_SEPARATOR . 'extension' . DIRECTORY_SEPARATOR . $extensionName . DIRECTORY_SEPARATOR.'sql'.DIRECTORY_SEPARATOR.'destroy.sql';
            if (file_exists($file)) {
                foreach (explode(";", file_get_contents($file)) as $line) {
                    if (trim($line)) {
                        $DB->query($line.';');
                    }
                }
            }
        }
        foreach (explode(";", file_get_contents(__DIR__."/../sql/destroy.sql")) as $line) {
            if (trim($line)) {
                $DB->query($line.';');
            }
        }
        db\migrations\MigrationManager::upgrade($this->app, false);
    }


    protected function getSiteQuotaUsedForTesting()
    {
        global $CONFIG;
        $siteQuotaRepository = new repositories\SiteQuotaRepository($this->app);
        return $siteQuotaRepository->loadByCode($CONFIG->newSiteHasQuotaCode);
    }

    protected function addCountriesToTestDB()
    {
        global $DB;
        $statInsert = $DB->prepare("INSERT INTO country (two_char_code,title,timezones,max_lat,max_lng,min_lat,min_lng) ".
            "VALUES (:two_char_code,:title,:timezones,:max_lat,:max_lng,:min_lat,:min_lng)");
        $statInsert->execute(array('two_char_code'=>'GB','title'=>'United Kingdom','timezones'=>'Europe/London','max_lat'=>null, 'max_lng'=>null, 'min_lat'=>null, 'min_lng'=>null));
        $statInsert->execute(array('two_char_code'=>'DE','title'=>'Germany','timezones'=>"Europe/Berlin,Europe/Busingen",'max_lat'=>null, 'max_lng'=>null, 'min_lat'=>null, 'min_lng'=>null));
    }
}
