<?php

namespace sysadmin\controllers;

use repositories\builders\UserAccountRepositoryBuilder;
use repositories\UserAccountRepository;
use repositories\UserPermissionsRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use repositories\SiteRepository;
use repositories\UserGroupRepository;

use sysadmin\ActionParser;
use sysadmin\forms\ActionForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserGroupController
{
    protected $parameters = array();

    protected function build($id, Request $request, Application $app)
    {
        $this->parameters = array();

        $ugr = new UserGroupRepository($app);
        $this->parameters['usergroup'] = $ugr->loadByIdInIndex($id);

        if (!$this->parameters['usergroup']) {
            $app->abort(404);
        }
    }

    public function index($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);

        if ($request->request->get('action') == "addpermission" && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $extension = $app['extensions']->getExtensionById($request->request->get("extension"));
            if ($extension) {
                $permission = $extension->getUserPermission($request->request->get("permission"));
                if ($permission) {
                    $ugr = new UserGroupRepository($app);
                    $ugr->addPermissionToGroup($permission, $this->parameters['usergroup'], $app['currentUser']);
                    return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                }
            }
        } elseif ($request->request->get('action') == "removepermission" && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $extension = $app['extensions']->getExtensionById($request->request->get("extension"));
            if ($extension) {
                $permission = $extension->getUserPermission($request->request->get("permission"));
                if ($permission) {
                    $ugr = new UserGroupRepository($app);
                    $ugr->removePermissionFromGroup($permission, $this->parameters['usergroup'], $app['currentUser']);
                    return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                }
            }
        }

        $form = $app['form.factory']->create(ActionForm::class);

        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);


            if ($form->isValid()) {
                $data = $form->getData();
                $action = new ActionParser($data['action']);

                if ($action->getCommand() == 'addusername') {
                    $uar = new UserAccountRepository($app);
                    $user = $uar->loadByUserName($action->getParam(0));
                    if ($user) {
                        $ugr = new UserGroupRepository($app);
                        $ugr->addUserToGroup($user, $this->parameters['usergroup'], $app['currentUser']);
                        return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                    }
                } elseif ($action->getCommand() == 'removeusername') {
                    $uar = new UserAccountRepository($app);
                    $user = $uar->loadByUserName($action->getParam(0));
                    if ($user) {
                        $ugr = new UserGroupRepository($app);
                        $ugr->removeUserFromGroup($user, $this->parameters['usergroup'], $app['currentUser']);
                        return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                    }
                } elseif ($action->getCommand() == 'includesanonymous') {
                    $ugr = new UserGroupRepository($app);
                    $this->parameters['usergroup']->setIsIncludesAnonymous($action->getParamBoolean(0));
                    $ugr->editIsIncludesAnonymous($this->parameters['usergroup'], $app['currentUser']);
                    return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                } elseif ($action->getCommand() == 'includesusers') {
                    $ugr = new UserGroupRepository($app);
                    $this->parameters['usergroup']->setIsIncludesUsers($action->getParamBoolean(0));
                    $ugr->editIsIncludesUser($this->parameters['usergroup'], $app['currentUser']);
                    return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                } elseif ($action->getCommand() == 'includesverifiedusers') {
                    $ugr = new UserGroupRepository($app);
                    $this->parameters['usergroup']->setIsIncludesVerifiedUsers($action->getParamBoolean(0));
                    $ugr->editIsIncludesVerifiedUser($this->parameters['usergroup'], $app['currentUser']);
                    return $app->redirect('/sysadmin/usergroup/'.$this->parameters['usergroup']->getId());
                }
            }
        }

        $this->parameters['form'] = $form->createView();

        $urb = new UserAccountRepositoryBuilder($app);
        $urb->setInUserGroup($this->parameters['usergroup']);
        $this->parameters['users'] = $urb->fetchAll();

        $r = new UserPermissionsRepository($app);
        $this->parameters['userpermissions'] = $r->getPermissionsForUserGroup($this->parameters['usergroup'], false);

        $this->parameters['userpermissionstoadd'] = array();
        foreach ($app['extensions']->getExtensionsIncludingCore() as $ext) {
            foreach ($ext->getUserPermissions() as $key) {
                $per = $ext->getUserPermission($key);
                if ($per->isForIndex() && !in_array($per, $this->parameters['userpermissions'])) {
                    $this->parameters['userpermissionstoadd'][] = $per;
                }
            }
        }

        return $app['twig']->render('sysadmin/usergroup/index.html.twig', $this->parameters);
    }
}
