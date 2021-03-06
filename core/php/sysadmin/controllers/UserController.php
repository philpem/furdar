<?php

namespace sysadmin\controllers;

use repositories\builders\AreaRepositoryBuilder;
use repositories\builders\EventRepositoryBuilder;
use repositories\builders\GroupRepositoryBuilder;
use repositories\builders\HistoryRepositoryBuilder;
use repositories\builders\SysadminCommentRepositoryBuilder;
use repositories\builders\VenueRepositoryBuilder;
use repositories\SysAdminCommentRepository;
use repositories\UserAccountPrivateFeedKeyRepository;
use repositories\UserAccountRememberMeRepository;
use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\builders\UserAccountResetRepositoryBuilder;
use repositories\builders\UserAccountVerifyEmailRepositoryBuilder;
use repositories\builders\UserWatchesGroupNotifyEmailRepositoryBuilder;
use repositories\builders\UserWatchesGroupPromptEmailRepositoryBuilder;
use repositories\builders\UserWatchesSiteGroupPromptEmailRepositoryBuilder;
use repositories\builders\UserWatchesSiteNotifyEmailRepositoryBuilder;
use repositories\builders\UserWatchesSitePromptEmailRepositoryBuilder;
use sysadmin\forms\ActionForm;
use sysadmin\ActionParser;
use repositories\UserAccountVerifyEmailRepository;
use repositories\builders\UserNotificationRepositoryBuilder;
use sysadmin\forms\ActionWithCommentForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserController
{
    protected $parameters = array();
    
    protected function build($id, Request $request, Application $app)
    {
        $this->parameters = array('group'=>null);

        $uar = new UserAccountRepository($app);
        $this->parameters['user'] = $uar->loadById($id);
        
        if (!$this->parameters['user']) {
            $app->abort(404);
        }
    }
    
    
    public function show($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);
        
        
        $form = $app['form.factory']->create(ActionWithCommentForm::class);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            
            
            if ($form->isValid()) {
                $data = $form->getData();
                $action = new ActionParser($data['action']);
                $uar = new UserAccountRepository($app);

                $redirect = false;

                if ($data['comment']) {
                    $scr = new SysAdminCommentRepository($app);
                    $scr->createAboutUser($this->parameters['user'], $data['comment'], $app['currentUser']);
                    $redirect = true;
                }

                if ($action->getCommand() == 'editor' && $action->getParam(0) == 'yes') {
                    $this->parameters['user']->setIsEditor(true);
                    $uar->edit($this->parameters['user']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'editor' && $action->getParam(0) == 'no') {
                    $this->parameters['user']->setIsEditor(false);
                    $uar->edit($this->parameters['user']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'sysadmin' && $action->getParam(0) == 'yes') {
                    $this->parameters['user']->setIsSystemAdmin(true);
                    $uar->edit($this->parameters['user']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'sysadmin' && $action->getParam(0) == 'no') {
                    $this->parameters['user']->setIsSystemAdmin(false);
                    $uar->edit($this->parameters['user']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'verifyemail') {
                    $uar->verifyEmail($this->parameters['user']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'resendverificationemail' && !$this->parameters['user']->getIsEmailVerified()) {
                    $repo = new UserAccountVerifyEmailRepository($app);
                    $verify = $repo->create($this->parameters['user']);
                    $verify->sendEmail($app, $this->parameters['user']);
                    $app['flashmessages']->addMessage('Sent');
                    $redirect = true;
                } elseif ($action->getCommand() == 'close') {
                    $uar->systemAdminShuts($this->parameters['user'], $app['currentUser']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'open') {
                    $uar->systemAdminOpens($this->parameters['user'], $app['currentUser']);
                    $redirect = true;
                } elseif ($action->getCommand() == 'email' && filter_var($action->getParam(0), FILTER_VALIDATE_EMAIL)) {
                    $checkNewEmail = $uar->loadByEmail($action->getParam(0));
                    if ($checkNewEmail) {
                        $app['flashmessages']->addError('That email address already exists in the system. User ID '. $checkNewEmail->getId());
                    } else {
                        $this->parameters['user']->setEmail($action->getParam(0));
                        $uar->editEmail($this->parameters['user']);
                        $redirect = true;
                    }
                } elseif ($action->getCommand() == 'purge' && $app['config']->sysAdminExtraPurgeUserPassword && $app['config']->sysAdminExtraPurgeUserPassword == $action->getParam(0)) {
                    $uar->purge($this->parameters['user']);
                    return $app->redirect('/sysadmin/user/');
                }

                if ($redirect) {
                    return $app->redirect('/sysadmin/user/'.$this->parameters['user']->getId());
                }
            }
        }
        
        $this->parameters['form'] = $form->createView();

        $sacrb = new SysadminCommentRepositoryBuilder($app);
        $sacrb->setUser($this->parameters['user']);
        $this->parameters['comments'] = $sacrb->fetchAll();

        $canPurge = true;
        foreach ($app['extensions']->getExtensionsIncludingCore() as $extension) {
            if (!$extension->canPurgeUser($this->parameters['user'])) {
                $canPurge = false;
            }
        }
        $this->parameters['canPurge'] = $canPurge;


        $userAccountRememberMeRepository = new UserAccountRememberMeRepository($app);
        $this->parameters['remember_me_last_used'] = $userAccountRememberMeRepository->getLastUsedForUser($this->parameters['user']);

        $userAccountPrivateFeedKeyRepository = new UserAccountPrivateFeedKeyRepository($app);
        $this->parameters['private_feed_key_last_used'] = $userAccountPrivateFeedKeyRepository->getLastUsedForUser($this->parameters['user']);

        return $app['twig']->render('sysadmin/user/show.html.twig', $this->parameters);
    }
    
    
    public function listNotificationPreferences($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);


        $repo = new \repositories\UserNotificationPreferenceRepository($app);

        $this->parameters['userNotificationPreferences'] = array();

        foreach ($app['extensions']->getExtensionsIncludingCore() as $extension) {
            foreach ($extension->getUserNotificationPreferenceTypes() as $type) {
                $preference = $extension->getUserNotificationPreference($type);
                $userPref = $repo->load(
                    $this->parameters['user'],
                    $preference->getUserNotificationPreferenceExtensionID(),
                    $preference->getUserNotificationPreferenceType()
                );
                $this->parameters['userNotificationPreferences'][] = array(
                    'pref' => $preference,
                    'userPref' => $userPref,
                );
            }
        }
        
        return $app['twig']->render('sysadmin/user/notificationPrefs.html.twig', $this->parameters);
    }


    public function verify($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);


        $rb = new UserAccountVerifyEmailRepositoryBuilder($app);
        $rb->setUser($this->parameters['user']);
        $this->parameters['verifies'] = $rb->fetchAll();

        return $app['twig']->render('sysadmin/user/verify.html.twig', $this->parameters);
    }

    public function reset($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);
        
        
        $rb = new UserAccountResetRepositoryBuilder($app);
        $rb->setUser($this->parameters['user']);
        $this->parameters['resets'] = $rb->fetchAll();
        
        
        return $app['twig']->render('sysadmin/user/reset.html.twig', $this->parameters);
    }

    
    public function listNotifications($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);
    
        $rb = new UserNotificationRepositoryBuilder($app);
        $rb->setLimit(40);
        $rb->setUser($this->parameters['user']);
        $this->parameters['notifications'] = $rb->fetchAll();

        return $app['twig']->render('/sysadmin/user/notifications.html.twig', $this->parameters);
    }

    public function eventEdited($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);

        $erb = new EventRepositoryBuilder($app);
        $erb->setEditedByUser($this->parameters['user']);
        $erb->setOrderByStartAt(true);
        $this->parameters['events'] = $erb->fetchAll();



        return $app['twig']->render('/sysadmin/user/event.edited.html.twig', $this->parameters);
    }

    public function areaEdited($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);

        $arb = new AreaRepositoryBuilder($app);
        $arb->setEditedByUser($this->parameters['user']);
        $this->parameters['areas'] = $arb->fetchAll();



        return $app['twig']->render('/sysadmin/user/area.edited.html.twig', $this->parameters);
    }

    public function venueEdited($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);

        $vrb = new VenueRepositoryBuilder($app);
        $vrb->setEditedByUser($this->parameters['user']);
        $this->parameters['venues'] = $vrb->fetchAll();



        return $app['twig']->render('/sysadmin/user/venue.edited.html.twig', $this->parameters);
    }

    public function groupEdited($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);

        $grb = new GroupRepositoryBuilder($app);
        $grb->setEditedByUser($this->parameters['user']);
        $this->parameters['groups'] = $grb->fetchAll();



        return $app['twig']->render('/sysadmin/user/group.edited.html.twig', $this->parameters);
    }
}
