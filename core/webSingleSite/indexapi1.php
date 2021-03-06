<?php


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use repositories\SiteRepository;
use repositories\UserInSiteRepository;
use repositories\UserWatchesSiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

if (!$CONFIG->isSingleSiteMode) {
    die("Single Site Mode Not Enabled");
}

$app->before(function (Request $request) use ($app) {
    # ////////////// Site
    $siteRepository = new SiteRepository($app);
    $site = $siteRepository->loadById($app['config']->singleSiteID);
    if (!$site) {
        die("404 Not Found"); // TODO
    }
    
    $app['twig']->addGlobal('currentSite', $site);
    $app['currentSite'] = $site;
    
    # ////////////// Site closed
    if ($app['currentSite']->getIsClosedBySysAdmin()) {
        $app['twig']->addGlobal('currentUserInSite', null);
        $app['twig']->addGlobal('currentUserCanAdminSite', false);
        $app['twig']->addGlobal('currentUserCanEditSite', false);
        return new Response($app['twig']->render('site/closed_by_sys_admin.html.twig', array()));
    }
    
    # ////////////// Timezone
    $timezone = "";
    if (isset($_GET['mytimezone']) && in_array($_GET['mytimezone'], $app['currentSite']->getCachedTimezonesAsList())) {
        $timezone = $_GET['mytimezone'];
    } elseif (isset($_COOKIE["site".$app['currentSite']->getId()."timezone"]) && in_array($_COOKIE["site".$app['currentSite']->getId()."timezone"], $site->getCachedTimezonesAsList())) {
        $timezone = $_COOKIE["site".$app['currentSite']->getId()."timezone"];
    } elseif (in_array('Europe/London', $site->getCachedTimezonesAsList())) {
        $timezone = 'Europe/London';
    } else {
        $timezone  = $site->getCachedTimezonesAsList()[0];
    }
    $app['twig']->addGlobal('currentTimeZone', $timezone);
    $app['currentTimeZone'] = $timezone;
});


define('FRIENDLY_SLUG_REGEX', '\d[a-z\d\-]*');

$app->match('/api1/', "siteapi1\controllers\IndexController::index") ;

require APP_ROOT_DIR.'/core/webSite/indexapi1.routes.php';
require APP_ROOT_DIR.'/core/webIndex/indexapi1.routes.php';

foreach ($CONFIG->extensions as $extensionName) {
    if (file_exists(APP_ROOT_DIR.'/extension/'.$extensionName.'/webIndex/indexapi1.routes.php')) {
        require APP_ROOT_DIR.'/extension/'.$extensionName.'/webIndex/indexapi1.routes.php';
    }
    if (file_exists(APP_ROOT_DIR.'/extension/'.$extensionName.'/webSite/indexapi1.routes.php')) {
        require APP_ROOT_DIR.'/extension/'.$extensionName.'/webSite/indexapi1.routes.php';
    }
    if (file_exists(APP_ROOT_DIR.'/extension/'.$extensionName.'/webSingleSite/indexapi1.routes.php')) {
        require APP_ROOT_DIR.'/extension/'.$extensionName.'/webSingleSite/indexapi1.routes.php';
    }
}


$app->run();
