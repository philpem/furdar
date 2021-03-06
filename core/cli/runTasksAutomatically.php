<?php
define('APP_ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once(defined('COMPOSER_ROOT_DIR') ? COMPOSER_ROOT_DIR : APP_ROOT_DIR).'/vendor/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoloadCLI.php';

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

$verbosePrint = true;

if ($app['config']->siteReadOnly) {
    if ($verbosePrint) {
        print "Site is in READ ONLY mode; nothing will be done\n";
    }
    exit(1);
}
if ($verbosePrint) {
    print "Starting all tasks check ". $app['timesource']->getDateTime()->format("c")."\n";
}
foreach ($app['extensions']->getExtensionsIncludingCore() as $extension) {
    if ($verbosePrint) {
        print "Extension ".$extension->getId()."\n";
    }
    foreach ($extension->getTasks() as $task) {
        if ($verbosePrint) {
            print "    Task ".$task->getTaskId()."\n";
        }
        try {
            $task->runAutomaticallyNowIfShould($verbosePrint);
        } catch (\Exception $e) {
            $app['monolog']->addError("Exception Running Tasks Automatically: ".$e->getMessage());
            // If we get an exception from runAutomaticallyNowIfShould() we want to crash out so no more tasks are run.
            // The Exception may have left the DB or other resources in a bad state.
            throw $e;
        }
    }
}
if ($verbosePrint) {
    print "Done all tasks check ". $app['timesource']->getDateTime()->format("c")."\n";
}


exit(0);
