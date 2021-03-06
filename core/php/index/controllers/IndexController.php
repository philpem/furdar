<?php

namespace index\controllers;

use models\SiteEditMetaDataModel;
use repositories\UserNotificationPreferenceRepository;
use Silex\Application;
use index\forms\CreateForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use repositories\SiteRepository;
use Symfony\Component\Form\FormError;
use repositories\builders\SiteRepositoryBuilder;
use repositories\CountryRepository;
use repositories\SiteQuotaRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IndexController
{
    public function index(Application $app)
    {
        $sites = array();
        $repo  = new SiteRepository($app);
        if (isset($_COOKIE['sitesSeen'])) {
            foreach (explode(",", $_COOKIE['sitesSeen']) as $siteID) {
                if (intval($siteID) > 0) {
                    $site = $repo->loadById($siteID);
                    if ($site && !$site->getIsClosedBySysAdmin() && $site->getSlug() != $app['config']->siteSlugDemoSite) {
                        $sites[$site->getId()] = $site;
                    }
                }
            }
        }
        
        if ($app['currentUser']) {
            $srb = new SiteRepositoryBuilder($app);
            $srb->setIsOpenBySysAdminsOnly(true);
            $srb->setUserInterestedIn($app['currentUser']);
            foreach ($srb->fetchAll() as $site) {
                $sites[$site->getId()] = $site;
            }

            $userNotificationPreferenceRepo= new UserNotificationPreferenceRepository($app);


            return $app['twig']->render('index/index/index.loggedin.html.twig', array(
                'sites'=>$sites,
                'showUserEmailPreferencesPrompt' => !$userNotificationPreferenceRepo->hasUserExpressedAnyPreferences($app['currentUser']),
            ));
        } else {
            return $app['twig']->render('index/index/index.loggedout.html.twig', array(
                'sites'=>$sites,
            ));
        }
    }
    
    public function myTimeZone(Application $app)
    {
        return $app['twig']->render('index/index/myTimeZone.html.twig', array(
            ));
    }
    
    public function create(Request $request, Application $app)
    {
        $siteRepository = new SiteRepository($app);
                
        $form = $app['form.factory']->create(CreateForm::class, null, array('app'=>$app));
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            $data = $form->getData();
            
            $site = $siteRepository->loadBySlug($data['slug']);
            if ($site) {
                $form->addError(new FormError('That address is already taken'));
            }
            
            if ($form->isValid()) {
                $site = new SiteModel();
                $site->setSlug($data['slug']);
                $site->setTitle($data['title']);
                if ($data['read'] == 'public') {
                    $site->setIsListedInIndex(true);
                    $site->setIsWebRobotsAllowed(true);
                } else {
                    $site->setIsListedInIndex(false);
                    $site->setIsWebRobotsAllowed(false);
                }
                if ($data['write'] == 'public') {
                    $isAllUsersEditors = true;
                } else {
                    $isAllUsersEditors = false;
                }
                $site->setPromptEmailsDaysInAdvance($app['config']->newSitePromptEmailsDaysInAdvance);

                $countryRepository = new CountryRepository($app);
                $siteQuotaRepository = new SiteQuotaRepository($app);

                $siteEditMetaDataModel = new SiteEditMetaDataModel();
                $siteEditMetaDataModel->setUserAccount($app['currentUser']);
                $siteEditMetaDataModel->setFromRequest($request);

                $siteRepository->createWithMetaData(
                    $site,
                    $app['currentUser'],
                    array( $countryRepository->loadByTwoCharCode("GB") ),
                    $siteQuotaRepository->loadByCode($app['config']->newSiteHasQuotaCode),
                    $siteEditMetaDataModel,
                    $isAllUsersEditors
                );

                if ($app['config']->hasSSL) {
                    return $app->redirect("https://".$site->getSlug().".".$app['config']->webSiteDomainSSL);
                } else {
                    return $app->redirect("http://".$site->getSlug().".".$app['config']->webSiteDomain);
                }
            }
        }

        $sites = array();
        $repo  = new SiteRepository($app);
        if (isset($_COOKIE['sitesSeen'])) {
            foreach (explode(",", $_COOKIE['sitesSeen']) as $siteID) {
                if (intval($siteID) > 0) {
                    $site = $repo->loadById($siteID);
                    if ($site && !$site->getIsClosedBySysAdmin() && $site->getSlug() != $app['config']->siteSlugDemoSite) {
                        $sites[$site->getId()] = $site;
                    }
                }
            }
        }

        $srb = new SiteRepositoryBuilder($app);
        $srb->setIsOpenBySysAdminsOnly(true);
        $srb->setUserInterestedIn($app['currentUser']);
        foreach ($srb->fetchAll() as $site) {
            $sites[$site->getId()] = $site;
        }

        return $app['twig']->render('index/index/create.html.twig', array(
            'form'=>$form->createView(),
            'sites'=>$sites,
        ));
    }
    
    public function about(Application $app)
    {
        return $app['twig']->render('index/index/about.html.twig', array());
    }
    
    
    public function terms(Application $app)
    {
        return $app['twig']->render('index/index/terms.html.twig', array());
    }
    
    
    public function privacy(Application $app)
    {
        return $app['twig']->render('index/index/privacy.html.twig', array());
    }
    
    
    public function credits(Application $app)
    {
        return $app['twig']->render('index/index/credits.html.twig', array());
    }
    
    
    
    public function discover(Application $app)
    {
        $srb = new SiteRepositoryBuilder($app);
        $srb->setIsListedInIndexOnly(true);
        $srb->setIsOpenBySysAdminsOnly(true);
        $sites = $srb->fetchAll();

        return $app['twig']->render('index/index/discover.html.twig', array(
            'sites'=>$sites
        ));
    }
}
