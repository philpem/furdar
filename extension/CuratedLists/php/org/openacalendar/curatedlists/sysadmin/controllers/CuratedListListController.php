<?php

namespace org\openacalendar\curatedlists\sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
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
    public function index($siteid, Request $request, Application $app)
    {
        $sr = new SiteRepository($app);
        $site = $sr->loadById($siteid);
        
        if (!$site) {
            die("404");
        }
        
        $rb = new CuratedListRepositoryBuilder($app);
        $rb->setSite($site);
        $curatedlists = $rb->fetchAll();
        
        return $app['twig']->render('sysadmin/curatedlistlist/index.html.twig', array(
                'site'=>$site,
                'curatedlists'=>$curatedlists,
            ));
    }
}
