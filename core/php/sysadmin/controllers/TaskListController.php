<?php

namespace sysadmin\controllers;

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
class TaskListController
{
    public function index(Request $request, Application $app)
    {
        $tasks = array();
        foreach ($app['extensions']->getExtensionsIncludingCore() as $ext) {
            $tasks = array_merge($tasks, $ext->getTasks());
        }

        return $app['twig']->render('sysadmin/tasklist/index.html.twig', array(
            'tasks'=>$tasks,
        ));
    }
}
