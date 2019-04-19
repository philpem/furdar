<?php

namespace site\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\CountryModel;
use repositories\builders\CountryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CountryListController
{
    public function index(Application $app)
    {
        $crb = new CountryRepositoryBuilder($app);
        $crb->setSiteIn($app['currentSite']);
        $countries = $crb->fetchAll();
        
        
        return $app['twig']->render('site/countrylist/index.html.twig', array(
                'countries'=>$countries,
            ));
    }
}
