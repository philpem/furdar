<?php
use import\ImportRunner;
use repositories\SiteRepository;

define('APP_ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once(defined('COMPOSER_ROOT_DIR') ? COMPOSER_ROOT_DIR : APP_ROOT_DIR).'/vendor/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoloadCLI.php';

/**
 *
 *
 * This script runs one specific import only.
 *
 * Normally you shouldn't use it - use runTasksAutomatically instead.
 *
 * But sometimes you want to force a run now, with no delay.
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

$siteRepository = new SiteRepository($app);
$importRepository = new \repositories\ImportRepository($app);

$i = 1;
while (substr($argv[$i], 0, 1) == '-') {
    $i++;
}
$siteSlug = $argv[$i];
$importSlug = $argv[$i+1];


$site = $siteRepository->loadBySlug($siteSlug);
if (!$site) {
    print "No Site!".PHP_EOL;
    die();
}
print "Site: ". $site->getTitle(). PHP_EOL;

$import = $importRepository->loadBySlug($site, $importSlug);
if (!$import) {
    print "No Import!".PHP_EOL;
    die();
}
print "Import: ". $import->getTitle().PHP_EOL;
print "Import URL: ". $import->getUrl().PHP_EOL;

if (!$import->getIsEnabled()) {
    print "NOT ENABLED!".PHP_EOL;
    die();
}

$runner = new ImportRunner($app);
$runner->go($import);

print "Done. ".PHP_EOL;
