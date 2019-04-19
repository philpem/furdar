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
$extensionID = isset($argv[1]) ? trim($argv[1]) : null;
$taskID = isset($argv[2]) ? trim($argv[2]) : null;

if (!$extensionID && !$taskID) {
    print "Must set a task!\n\n";
    foreach ($app['extensions']->getExtensionsIncludingCore() as $extension) {
        foreach ($extension->getTasks() as $task) {
            print "  ". $task->getExtensionId(). "  ". $task->getTaskId(). " \n";
        }
    }
    exit(1);
}
if ($extensionID && !$taskID) {
    $taskID = $extensionID;
    $extensionID = 'org.openacalendar';
}

$extension = $app['extensions']->getExtensionById($extensionID);




foreach ($extension->getTasks() as $task) {
    if ($task->getTaskId() == $taskID) {
        if ($verbosePrint) {
            print "Found Task ".$task->getTaskId()."\n";
        }
        $task->runManuallyNowIfShould($verbosePrint);
    }
}



exit(0);
