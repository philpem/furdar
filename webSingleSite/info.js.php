<?php
require 'localConfig.php';
require_once (defined('COMPOSER_ROOT_DIR') ? COMPOSER_ROOT_DIR : APP_ROOT_DIR).'/vendor/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoloadWebApp.php';

use repositories\SiteRepository;
use repositories\UserHasNoEditorPermissionsInSiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
$siteRepository = new SiteRepository($app);
$site = $siteRepository->loadById($CONFIG->singleSiteID);
if (!$site) {
    die ("404 Not Found");
    // maybe could do something better here, but this will only happen if site config is broken
}

// ================ cache for a bit
// the v and u passed to this have no effect here - they are just cache busters
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 30*60));


// ================ Data!
$data  = array();
$data['installTitle'] = $CONFIG->installTitle;
// TODO would like to depreceate httpDomain and get scripts to just use httpDomainIndex & httpDomainSite for clarity
$data['httpDomain'] = $CONFIG->webSiteDomain;
$data['httpDomainIndex'] = $CONFIG->webSiteDomain;
$data['httpDomainSite'] = $CONFIG->webSiteDomain;
$data['isWebRobotsAllowed'] = $site->getIsWebRobotsAllowed();
$data['twitter'] = $CONFIG->contactTwitter;
$data['isSingleSiteMode'] = true;
if ($CONFIG->hasSSL) {
	$data['hasSSL'] = true;
	$data['httpsDomain'] = $CONFIG->webIndexDomainSSL;
	$data['httpsDomainIndex'] = $CONFIG->webIndexDomainSSL;
	$data['httpsDomainSite'] = $CONFIG->webSiteDomainSSL;
} else {
	$data['hasSSL'] = false;
}
$user = userGetCurrent();
if ($user) {
	$data['currentUser'] = array(
		'username'=> $user->getUsername(),
	);
} else {
	$data['currentUser'] = false;
}


$removeEditorPermissions = false;
$userHasNoEditorPermissionsInSiteRepo = new UserHasNoEditorPermissionsInSiteRepository($app);
if ($app['currentUser'] && $userHasNoEditorPermissionsInSiteRepo->isUserInSite($app['currentUser'], $site)) {
	$removeEditorPermissions = true;
}

$userPermissionsRepo = new \repositories\UserPermissionsRepository($app);
$currentUserPermissions = $userPermissionsRepo->getPermissionsForUserInSite($user, $site, $removeEditorPermissions, true);
$data['currentUserPermissions'] = $currentUserPermissions->getAsArrayForJSON();

header('Content-Type: application/javascript');
print "var config = ".json_encode($data);

