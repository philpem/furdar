<?php

namespace sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\SiteRepository;
use repositories\builders\SiteRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteListController
{
    public function index(Request $request, Application $app)
    {
        $erb = new SiteRepositoryBuilder($app);
        $sites = $erb->fetchAll();
        
        return $app['twig']->render('sysadmin/sitelist/index.html.twig', array(
                'sites'=>$sites,
            ));
    }
}
