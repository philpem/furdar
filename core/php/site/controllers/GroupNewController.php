<?php

namespace site\controllers;

use models\GroupEditMetaDataModel;
use Silex\Application;
use site\forms\GroupNewForm;
use site\forms\GroupEditForm;
use site\forms\EventNewForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\GroupModel;
use models\EventModel;
use repositories\GroupRepository;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupNewController
{
    public function newGroup(Request $request, Application $app)
    {

        /////////////////////////////////////////////////////// Check Permissions and Prompt IF NEEDED

        if (!$app['currentUser'] && !$app['currentUserActions']->has("org.openacalendar", "groupNew") &&  $app['anyVerifiedUserActions']->has("org.openacalendar", "groupNew")) {
            return $app['twig']->render('site/groupnew/new.useraccountneeded.html.twig', array());
        }

        /////////////////////////////////////////////////////// Carry On



        $group = new GroupModel();
        
        $form = $app['form.factory']->create(GroupNewForm::class, $group, array('defaultTitle'=>$request->query->get('title')));

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $groupEditMetaDataModel = new GroupEditMetaDataModel();
                $groupEditMetaDataModel->setUserAccount($app['currentUser']);
                $groupEditMetaDataModel->setFromRequest($request);

                $groupRepository = new GroupRepository($app);
                $groupRepository->createWithMetaData($group, $app['currentSite'], $groupEditMetaDataModel);
                
                return $app->redirect("/group/".$group->getSlugForUrl());
            }
        }
        
        
        return $app['twig']->render('site/groupnew/new.html.twig', array(
                'form'=>$form->createView(),
            ));
    }
}
