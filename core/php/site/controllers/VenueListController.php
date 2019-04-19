<?php

namespace site\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\VenueModel;
use repositories\VenueRepository;
use repositories\builders\VenueRepositoryBuilder;
use repositories\builders\filterparams\VenueFilterParams;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueListController
{
    public function index(Application $app)
    {
        $params = new VenueFilterParams($app);
        $params->set($_GET);
        $params->getVenueRepositoryBuilder()->setSite($app['currentSite']);
        $params->getVenueRepositoryBuilder()->setIncludeDeleted(false);
        $params->getVenueRepositoryBuilder()->setIncludeMediasSlugs(true);
        
        $venues = $params->getVenueRepositoryBuilder()->fetchAll();
        
        return $app['twig']->render('site/venuelist/index.html.twig', array(
                'venues'=>$venues,
                'venueListFilterParams'=>$params,
            ));
    }
}
