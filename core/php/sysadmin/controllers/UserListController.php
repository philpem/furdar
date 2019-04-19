<?php

namespace sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\builders\UserAccountRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserListController
{
    public function index(Request $request, Application $app)
    {
        $erb = new UserAccountRepositoryBuilder($app);
        $users = $erb->fetchAll();
        
        
        return $app['twig']->render('sysadmin/userlist/index.html.twig', array(
                'users'=>$users,
            ));
    }
}
