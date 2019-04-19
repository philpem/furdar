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
class TaskController
{
    protected $parameters;

    public function build($extid, $taskid, Request $request, Application $app)
    {
        $this->parameters = array('task'=>null);

        $ext = $app['extensions']->getExtensionById($extid);
        if ($ext) {
            foreach ($ext->getTasks() as $task) {
                if ($task->getTaskId() == $taskid) {
                    $this->parameters['task'] = $task;
                    return true;
                }
            }
        }

        return false;
    }

    public function index($extid, $taskid, Request $request, Application $app)
    {
        if (!$this->build($extid, $taskid, $request, $app)) {
            return $app->abort(404);
        }

        $tllrb = new TaskLogRepositoryBuilder($app);
        $tllrb->setLimit(100);
        $tllrb->setTask($this->parameters['task']);
        $this->parameters['tasklogs'] = $tllrb->fetchAll();

        return $app['twig']->render('sysadmin/task/index.html.twig', $this->parameters);
    }
}
