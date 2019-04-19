<?php

namespace site\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\ImportModel;
use repositories\builders\ImportRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportListController
{
    public function index(Application $app)
    {
        $grb = new ImportRepositoryBuilder($app);
        $grb->setSite($app['currentSite']);
        
        $imports = $grb->fetchAll();
        
        return $app['twig']->render('site/importlist/index.html.twig', array(
                'imports'=>$imports,
            ));
    }
}
