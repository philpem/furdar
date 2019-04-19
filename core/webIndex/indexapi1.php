<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */



$app->before(function (Request $request) use ($app) {
    # ////////////// Timezone
    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    $timezone = "";
    if (isset($_GET['mytimezone']) && in_array($_GET['mytimezone'], $timezones)) {
        $timezone = $_GET['mytimezone'];
    } elseif (isset($_COOKIE["siteIndextimezone"]) && in_array($_COOKIE["siteIndextimezone"], $timezones)) {
        $timezone = $_COOKIE["siteIndextimezone"];
    } else {
        $timezone = 'Europe/London';
    }
    $app['twig']->addGlobal('currentTimeZone', $timezone);
    $app['twig']->addGlobal('allowedTimeZones', $timezones);
    $app['currentTimeZone'] = $timezone;
});

require APP_ROOT_DIR.'/core/webIndex/indexapi1.routes.php';


foreach ($CONFIG->extensions as $extensionName) {
    if (file_exists(APP_ROOT_DIR.'/extension/'.$extensionName.'/webIndex/indexapi1.routes.php')) {
        require APP_ROOT_DIR.'/extension/'.$extensionName.'/webIndex/indexapi1.routes.php';
    }
}


$app->run();
