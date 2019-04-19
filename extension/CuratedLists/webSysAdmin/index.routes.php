<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

$app->match('/sysadmin/site/{siteid}/curatedlist', 'org\openacalendar\curatedlists\sysadmin\controllers\CuratedListListController::index')
        ->assert('siteid', '\d+');
$app->match('/sysadmin/site/{siteid}/curatedlist/', 'org\openacalendar\curatedlists\sysadmin\controllers\CuratedListListController::index')
        ->assert('siteid', '\d+');
$app->match('/sysadmin/site/{siteid}/curatedlist/{slug}', 'org\openacalendar\curatedlists\sysadmin\controllers\CuratedListController::index')
        ->assert('siteid', '\d+')
        ->assert('slug', '\d+');
$app->match('/sysadmin/site/{siteid}/curatedlist/{slug}/', 'org\openacalendar\curatedlists\sysadmin\controllers\CuratedListController::index')
        ->assert('siteid', '\d+')
        ->assert('slug', '\d+');
