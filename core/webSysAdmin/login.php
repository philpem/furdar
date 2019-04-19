<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


///////////////////////////////////////////// SECURITY

if (!$app['currentUser']) {
    die("No");
}
if (!$app['currentUser']->getIsSystemAdmin()) {
    die("No");
}


///////////////////////////////////////////// APP

$app->before(function (Request $request) use ($app) {
    # ////////////// Timezone
    $timezone = $app['config']->sysAdminTimeZone;
    $app['twig']->addGlobal('currentTimeZone', $timezone);
    $app['currentTimeZone'] = $timezone;
});


$app->match('/', "sysadmin\controllers\LogInController::index");
$app->run();
