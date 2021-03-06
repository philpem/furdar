<?php

namespace site\controllers;

use models\AreaEditMetaDataModel;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\CountryRepository;
use repositories\CountryInSiteRepository;
use site\forms\AreaNewInCountryForm;
use models\AreaModel;
use repositories\AreaRepository;
use repositories\builders\filterparams\EventFilterParams;
use repositories\builders\AreaRepositoryBuilder;
use repositories\builders\VenueRepositoryBuilder;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class CountryController
{
    protected $parameters = array();
    
    protected function build($slug, Request $request, Application $app)
    {
        $this->parameters = array();
        
        $gr = new CountryRepository($app);
        // we must accept both ID and Slug. Slug is proper one to use, but some JS needs to load by ID.
        $this->parameters['country'] = intval($slug) ? $gr->loadById($slug) :  $gr->loadByTwoCharCode($slug);
        if (!$this->parameters['country']) {
            return false;
        }
        
        // check this country is or was valid for this site
        $countryInSiteRepo = new CountryInSiteRepository($app);
        if (!$countryInSiteRepo->isCountryInSite($this->parameters['country'], $app['currentSite'])) {
            return false;
        }
        
        $areaRepoBuilder = new AreaRepositoryBuilder($app);
        $areaRepoBuilder->setSite($app['currentSite']);
        $areaRepoBuilder->setCountry($this->parameters['country']);
        $areaRepoBuilder->setNoParentArea(true);
        $areaRepoBuilder->setIncludeDeleted(false);
        $this->parameters['childAreas'] = $areaRepoBuilder->fetchAll();

        $app['currentUserActions']->set(
            "org.openacalendar",
            "actionAreaNew",
            $app['currentUserPermissions']->hasPermission("org.openacalendar", "AREAS_CHANGE")
        );
        
        return true;
    }
    
    public function show($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Country does not exist.");
        }
        
        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setCountry($this->parameters['country']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setIncludeAreaInformation(true);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setIncludeVenueInformation(true);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setIncludeMediasSlugs(true);
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $this->parameters['country']);
        if ($app['currentUser']) {
            $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $this->parameters['eventListFilterParams']->set($_GET);
        
        $this->parameters['events'] = $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->fetchAll();
        
                

        
        return $app['twig']->render('site/country/show.html.twig', $this->parameters);
    }
    
    
    public function calendarNow($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Country does not exist.");
        }

        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setCountry($this->parameters['country']);
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $this->parameters['country']);
        $this->parameters['eventListFilterParams']->setFallBackFrom(true);
        $this->parameters['eventListFilterParams']->set($_GET);

        $this->parameters['calendar'] = new \RenderCalendar($app, $this->parameters['eventListFilterParams']);

        if ($app['currentUser']) {
            $this->parameters['calendar']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $this->parameters['calendar']->byDate(\TimeSource::getDateTime(), 31, true);
        
        list($this->parameters['prevYear'], $this->parameters['prevMonth'], $this->parameters['nextYear'], $this->parameters['nextMonth']) = $this->parameters['calendar']->getPrevNextLinksByMonth();
        
        $this->parameters['pageTitle'] = $this->parameters['country']->getTitle();
        return $app['twig']->render('/site/country/calendar.monthly.html.twig', $this->parameters);
    }
    
    public function calendar($slug, $year, $month, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Country does not exist.");
        }

        $this->parameters['eventListFilterParams'] = new EventFilterParams($app, null, $app['currentSite']);
        $this->parameters['eventListFilterParams']->getEventRepositoryBuilder()->setCountry($this->parameters['country']);
        $this->parameters['eventListFilterParams']->setHasTagControl($app['currentSiteFeatures']->has('org.openacalendar', 'Tag'));
        $this->parameters['eventListFilterParams']->setHasGroupControl($app['currentSiteFeatures']->has('org.openacalendar', 'Group'));
        $this->parameters['eventListFilterParams']->setHasAreaControl($app['currentSiteFeatures']->has('org.openacalendar', 'PhysicalEvents'), $this->parameters['country']);
        $this->parameters['eventListFilterParams']->setFallBackFrom(true);
        $this->parameters['eventListFilterParams']->set($_GET);

        $this->parameters['calendar'] = new \RenderCalendar($app, $this->parameters['eventListFilterParams']);

        if ($app['currentUser']) {
            $this->parameters['calendar']->getEventRepositoryBuilder()->setUserAccount($app['currentUser'], true);
        }
        $this->parameters['calendar']->byMonth($year, $month, true);
        
        list($this->parameters['prevYear'], $this->parameters['prevMonth'], $this->parameters['nextYear'], $this->parameters['nextMonth']) = $this->parameters['calendar']->getPrevNextLinksByMonth();
        
        $this->parameters['pageTitle'] = $this->parameters['country']->getTitle();
        return $app['twig']->render('/site/country/calendar.monthly.html.twig', $this->parameters);
    }
    
    public function newSplash($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Country does not exist.");
        }
    
        return $app['twig']->render('site/country/new.html.twig', $this->parameters);
    }
    
    public function newArea($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Country does not exist.");
        }
        
        $area = new AreaModel();
        
        $form = $app['form.factory']->create(AreaNewInCountryForm::class, $area);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $areaEditMetaData = new AreaEditMetaDataModel();
                $areaEditMetaData->setUserAccount($app['currentUser']);
                $areaEditMetaData->setFromRequest($request);

                $areaRepository = new AreaRepository($app);
                $areaRepository->createWithMetaData($area, $app['currentSite'], $this->parameters['country'], $areaEditMetaData, null);
                // don't need to call $areaRepository->buildCacheAreaHasParent($area); - there are no parents!
                return $app->redirect("/area/".$area->getSlug());
            }
        }
        
        $this->parameters['form'] = $form->createView();
        return $app['twig']->render('site/country/newarea.html.twig', $this->parameters);
    }
    
    
    
    public function infoJson($slug, Request $request, Application $app)
    {
        if (!$this->build($slug, $request, $app)) {
            $app->abort(404, "Country does not exist.");
        }
        
        $data = array(
                'country'=>array(
                    'code'=>$this->parameters['country']->getTwoCharCode(),
                    'title'=>$this->parameters['country']->getTitle(),
                    'max_lat'=>$this->parameters['country']->getMaxLat(),
                    'min_lat'=>$this->parameters['country']->getMinLat(),
                    'max_lng'=>$this->parameters['country']->getMaxLng(),
                    'min_lng'=>$this->parameters['country']->getMinLng(),
                    'timezones'=>$this->parameters['country']->getTimezonesAsList(),
                ),
                'childAreas'=>array(),
                'venues'=>array(),
            );
        
        foreach ($this->parameters['childAreas'] as $childArea) {
            $data['childAreas'][] = array(
                'slug'=>$childArea->getSlug(),
                'title'=>$childArea->getTitle(),
                'max_lat'=>$childArea->getMaxLat(),
                'max_lng'=>$childArea->getMaxLng(),
                'min_lat'=>$childArea->getMinLat(),
                'min_lng'=>$childArea->getMinLng(),
            );
        }

        if (isset($_GET['includeVenues']) && $_GET['includeVenues']) {
            $vrb = new VenueRepositoryBuilder($app);
            $vrb->setIncludeDeleted(false);
            $vrb->setSite($app['currentSite']);
            $vrb->setCountry($this->parameters['country']);
            foreach ($vrb->fetchAll() as $venue) {
                $data['venues'][$venue->getId()] = array(
                        'slug'=>$venue->getSlug(),
                        'title'=>$venue->getTitle(),
                        'lat'=>$venue->getLat(),
                        'lng'=>$venue->getLng(),
                    );
            }
        }
        
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        $response->setPublic();
        $response->setMaxAge($app['config']->cacheFeedsInSeconds);
        return $response;
    }
}
