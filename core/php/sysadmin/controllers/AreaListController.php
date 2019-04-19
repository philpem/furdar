<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\builders\AreaRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AreaListController
{
    public function index($siteid, Request $request, Application $app)
    {
        $sr = new SiteRepository($app);
        $site = $sr->loadById($siteid);
        
        if (!$site) {
            die("404");
        }
        
        $arb = new AreaRepositoryBuilder($app);
        $arb->setSite($site);
        $areas = $arb->fetchAll();
        
        return $app['twig']->render('sysadmin/arealist/index.html.twig', array(
                'site'=>$site,
                'areas'=>$areas,
            ));
    }
}
