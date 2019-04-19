<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\GroupHistoryModel;
use models\EventHistoryModel;
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
        $historyRepositoryBuilder = new HistoryRepositoryBuilder($app);
        $historyRepositoryBuilder->setSite($app['currentSite']);
        $historyRepositoryBuilder->getHistoryRepositoryBuilderConfig()->setLimit(200);
        
        
        
        return $app['twig']->render('site/history/index.html.twig', array(
                'historyItems'=>$historyRepositoryBuilder->fetchAll(),
            ));
    }
}
