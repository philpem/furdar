<?php

namespace org\openacalendar\curatedlists\site\controllers;

use org\openacalendar\curatedlists\repositories\builders\filterparams\CuratedListFilterParams;
use Silex\Application;
use org\openacalendar\curatedlists\repositories\builders\CuratedListRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CuratedListListController
{
    public function index(Application $app)
    {
        $params = new CuratedListFilterParams($app);
        $params->set($_GET);
        $params->getCuratedListRepositoryBuilder()->setSite($app['currentSite']);
        $params->getCuratedListRepositoryBuilder()->setIncludeDeleted(false);

        $lists = $params->getCuratedListRepositoryBuilder()->fetchAll();

        return $app['twig']->render('site/curatedlistlist/index.html.twig', array(
            'curatedlists'=>$lists,
            'curatedListListFilterParams'=>$params,
        ));
    }
}
