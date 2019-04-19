<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\SiteQuotaModel;
use repositories\SiteQuotaRepository;
use sysadmin\forms\ActionForm;
use sysadmin\ActionParser;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteQuotaController
{
    protected $parameters = array();
    
    protected function build($code, Request $request, Application $app)
    {
        $this->parameters = array();

        $sqr = new SiteQuotaRepository($app);
        $this->parameters['sitequota'] = $sqr->loadByCode($code);
        
        if (!$this->parameters['sitequota']) {
            $app->abort(404);
        }
    }
    
    public function show($code, Request $request, Application $app)
    {
        $this->build($code, $request, $app);
        
        return $app['twig']->render('sysadmin/sitequota/show.html.twig', $this->parameters);
    }
}
