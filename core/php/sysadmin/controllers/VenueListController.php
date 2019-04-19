<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\builders\VenueRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueListController
{
    public function index($siteid, Request $request, Application $app)
    {
        $sr = new SiteRepository($app);
        $site = $sr->loadById($siteid);
        
        if (!$site) {
            die("404");
        }
        
        $vrb = new VenueRepositoryBuilder($app);
        $vrb->setSite($site);
        $venues = $vrb->fetchAll();
        
        return $app['twig']->render('sysadmin/venuelist/index.html.twig', array(
                'site'=>$site,
                'venues'=>$venues,
            ));
    }
}
