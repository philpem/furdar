<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\builders\SiteQuotaRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteQuotaListController
{
    public function index(Request $request, Application $app)
    {
        $sqrb = new SiteQuotaRepositoryBuilder($app);
        $sitequotas = $sqrb->fetchAll();
        
        return $app['twig']->render('sysadmin/sitequotalist/index.html.twig', array(
                'sitequotas'=>$sitequotas,
            ));
    }
}
