<?php

namespace sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\EventRepository;
use repositories\builders\HistoryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class HistoryController
{
    public function index(Request $request, Application $app)
    {
        $hrb = new HistoryRepositoryBuilder($app);
        
        
        return $app['twig']->render('sysadmin/history/index.html.twig', array(
                'historyItems'=>$hrb->fetchAll(),
            ));
    }
}
