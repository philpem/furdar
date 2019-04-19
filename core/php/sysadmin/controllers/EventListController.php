<?php

namespace sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\SiteRepository;
use repositories\builders\SiteRepositoryBuilder;
use repositories\builders\EventRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventListController
{
    public function index($siteid, Request $request, Application $app)
    {
        $sr = new SiteRepository($app);
        $site = $sr->loadById($siteid);
        
        if (!$site) {
            die("404");
        }
        
        $erb = new EventRepositoryBuilder($app);
        $erb->setSite($site);
        $erb->setOrderByStartAt(true);
        $events = $erb->fetchAll();
        
        return $app['twig']->render('sysadmin/eventlist/index.html.twig', array(
                'site'=>$site,
                'events'=>$events,
            ));
    }
}
