<?php

namespace site\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\GroupModel;
use repositories\GroupRepository;
use repositories\builders\GroupRepositoryBuilder;
use repositories\builders\filterparams\GroupFilterParams;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupListController
{
    public function index(Application $app)
    {
        $params = new GroupFilterParams($app);
        $params->set($_GET);
        $params->getGroupRepositoryBuilder()->setSite($app['currentSite']);
        $params->getGroupRepositoryBuilder()->setIncludeDeleted(false);
        $params->getGroupRepositoryBuilder()->setIncludeMediasSlugs(true);
        
        $groups = $params->getGroupRepositoryBuilder()->fetchAll();
        
        return $app['twig']->render('site/grouplist/index.html.twig', array(
                'groups'=>$groups,
                'groupListFilterParams'=>$params,
            ));
    }
}
