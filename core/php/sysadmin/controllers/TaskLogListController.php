<?php

namespace sysadmin\controllers;

use repositories\builders\TaskLogRepositoryBuilder;
use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\SiteRepository;
use repositories\builders\SiteRepositoryBuilder;
use repositories\builders\TagRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TaskLogListController
{
    public function index(Request $request, Application $app)
    {
        $tllrb = new TaskLogRepositoryBuilder($app);
        $tllrb->setLimit(500);



        return $app['twig']->render('sysadmin/taskloglist/index.html.twig', array(
            'tasklogs'=>$tllrb->fetchAll(),
        ));
    }
}
