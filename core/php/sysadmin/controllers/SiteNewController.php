<?php

namespace sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\SiteRepository;
use repositories\UserAccountRepository;
use repositories\CountryRepository;
use repositories\SiteQuotaRepository;
use repositories\builders\SiteRepositoryBuilder;
use sysadmin\forms\NewSiteForm;
use Symfony\Component\Form\FormError;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteNewController
{
    public function index(Request $request, Application $app)
    {
        $form = $app['form.factory']->create(NewSiteForm::class, null, ['app'=>$app]);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            $data = $form->getData();
            $siteRepository = new SiteRepository($app);

            $site = $siteRepository->loadBySlug($data['slug']);
            if ($site) {
                $form->addError(new FormError('That address is already taken'));
            }

            if ($form->isValid()) {
                $userRepo = new UserAccountRepository($app);
                $user = $userRepo->loadByEmail($data['email']);
                if ($user) {
                    $data = $form->getData();
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

                    $siteRepository->create(
                        $site,
                        $user,
                        array( $countryRepository->loadByTwoCharCode("GB") ),
                        $siteQuotaRepository->loadByCode($app['config']->newSiteHasQuotaCode),
                        $isAllUsersEditors
                    );
                    return $app->redirect("/sysadmin/site/".$site->getId());
                } else {
                    $app['flashmessages']->addError('Existing user not found!');
                }
            }
        }



        return $app['twig']->render('sysadmin/sitenew/index.html.twig', array(
                'form'=>$form->createView(),
            ));
    }
}
