<?php

namespace site\controllers;

use repositories\UserPermissionsRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\UserAccountRepository;
use repositories\SiteAccessRequestRepository;
use repositories\builders\SiteAccessRequestRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AdminUserController
{
    protected $parameters = array();
    
    protected function build($username, Request $request, Application $app)
    {
        $this->parameters = array();

        $repo = new UserAccountRepository($app);
        $this->parameters['user'] =  $repo->loadByUserName($username);
        if (!$this->parameters['user']) {
            return false;
        }
        
        return true;
    }

    public function index($username, Request $request, Application $app)
    {
        if (!$this->build($username, $request, $app)) {
            $app->abort(404, "User does not exist.");
        }

        $userPermissionRepo = new UserPermissionsRepository($app);

        $this->parameters['userpermissions'] = $userPermissionRepo->getPermissionsForUserInSite($this->parameters['user'], $app['currentSite'], false, false)->getPermissions();

        return $app['twig']->render('site/adminuser/index.html.twig', $this->parameters);
    }
}
