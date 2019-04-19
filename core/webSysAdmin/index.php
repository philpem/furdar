<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;

/**
 *
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
if (!$WEBSESSION->has('sysAdminLastActive')) {
    header("Location: /authintosysadmin.php");
    die();
}
if ($WEBSESSION->get('sysAdminLastActive') + $CONFIG->sysAdminLogInTimeOutSeconds < TimeSource::time()) {
    header("Location: /authintosysadmin.php");
    die();
}
$WEBSESSION->set('sysAdminLastActive', \TimeSource::time());

///////////////////////////////////////////// APP


$app->before(function (Request $request) use ($app) {
    # ////////////// Timezone
    $timezone = $app['config']->sysAdminTimeZone;
    $app['twig']->addGlobal('currentTimeZone', $timezone);
    $app['currentTimeZone'] = $timezone;
});

require APP_ROOT_DIR.'/core/webSysAdmin/index.routes.php';


foreach ($CONFIG->extensions as $extensionName) {
    if (file_exists(APP_ROOT_DIR.'/extension/'.$extensionName.'/webSysAdmin/index.routes.php')) {
        require APP_ROOT_DIR.'/extension/'.$extensionName.'/webSysAdmin/index.routes.php';
    }
}

$app->run();
