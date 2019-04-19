<?php

namespace sysadmin\controllers;

use models\UserGroupEditMetaDataModel;
use models\UserGroupModel;
use repositories\builders\UserAccountRepositoryBuilder;
use repositories\UserPermissionsRepository;
use Silex\Application;
use site\forms\AdminUserGroupNewForm;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\UserGroupRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserGroupNewController
{
    public function index(Request $request, Application $app)
    {
        $userGroup = new UserGroupModel();

        $form = $app['form.factory']->create(AdminUserGroupNewForm::class, $userGroup);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userGroupEditMetaDataModel = new UserGroupEditMetaDataModel();
                $userGroupEditMetaDataModel->setUserAccount($app['currentUser']);
                $userGroupEditMetaDataModel->setFromRequest($request);

                $ugRepository = new UserGroupRepository($app);
                $ugRepository->createForIndexWithMetaData($userGroup, $userGroupEditMetaDataModel);
                return $app->redirect("/sysadmin/usergroup/".$userGroup->getId());
            }
        }

        return $app['twig']->render('sysadmin/usergroupnew/index.html.twig', array(
            'form'=>$form->createView(),
        ));
    }
}
