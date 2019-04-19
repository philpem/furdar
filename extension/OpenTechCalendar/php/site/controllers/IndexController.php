<?php

namespace site\controllers;

use repositories\UserNotificationPreferenceRepository;
use Silex\Application;
use index\forms\CreateForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use models\CountryModel;
use repositories\CountryRepository;
use repositories\UserAccountRepository;
use repositories\builders\EventRepositoryBuilder;
use repositories\builders\VenueRepositoryBuilder;
use repositories\UserWatchesSiteRepository;
use repositories\UserWatchesSiteStopRepository;
use repositories\builders\UserAccountRepositoryBuilder;
use repositories\UserNotificationRepository;

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
        global $FRONTPAGEAREAS, $FRONTPAGEAREASOTHER;
        require_once APP_ROOT_DIR.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'frontPageAreas.php';


        return $app['twig']->render('site/index/index.html.twig', array(
            'areaTree'=>$FRONTPAGEAREAS,
            'areasOther'=>$FRONTPAGEAREASOTHER,
        ));
    }
    
    public function myTimeZone(Application $app)
    {
        return $app['twig']->render('site/index/myTimeZone.html.twig', array(
            ));
    }
    
    
    
    public function watch(Request $request, Application $app)
    {
        if ($request->request->get('action')  && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $repo = new UserWatchesSiteRepository($app);
            if ($request->request->get('action') == 'watch') {
                $repo->startUserWatchingSite($app['currentUser'], $app['currentSite']);
            } elseif ($request->request->get('action') == 'unwatch') {
                $repo->stopUserWatchingSite($app['currentUser'], $app['currentSite']);
            }
            // redirect here because if we didn't the twig global and $app vars would be wrong (the old state)
            // this is an easy way to get round that.
            return $app->redirect('/watch');
        }
        
        return $app['twig']->render('site/index/watch.html.twig', array(
            ));
    }
    
    public function stopWatchingFromEmail($userid, $code, Request $request, Application $app)
    {
        $userRepo = new UserAccountRepository($app);
        $user = $userRepo->loadByID($userid);
        if (!$user) {
            $app['monolog']->addError("Failed stop watching site from email - user not known");
            die("NO"); // TODO
        }
        
        $userWatchesSiteStopRepo = new UserWatchesSiteStopRepository($app);
        $userWatchesSiteStop = $userWatchesSiteStopRepo->loadByUserAccountIDAndSiteIDAndAccessKey($user->getId(), $app['currentSite']->getId(), $code);
        if (!$userWatchesSiteStop) {
            $app['monolog']->addError("Failed stop watching site from email - user ".$user->getId()." - code wrong");
            die("NO"); // TODO
        }
        
        $userWatchesSiteRepo = new UserWatchesSiteRepository($app);
        $userWatchesSite = $userWatchesSiteRepo->loadByUserAndSite($user, $app['currentSite']);
        if (!$userWatchesSite || !$userWatchesSite->getIsWatching()) {
            $app['monolog']->addError("Failed stop watching site from email - user ".$user->getId()." - not watching");
            die("You don't watch this site"); // TODO
        }
        
        if ($request->request->get('action') == 'unwatch' && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()) {
            $userWatchesSiteRepo->stopUserWatchingSite($user, $app['currentSite']);
            // redirect here because if we didn't the twig global and $app vars would be wrong (the old state)
            // this is an easy way to get round that.
            $app['flashmessages']->addMessage("You have stopped watching this.");
            return $app->redirect('/');
        }
        
        return $app['twig']->render('site/index/stopWatchingFromEmail.html.twig', array(
                'user'=>$user,
            ));
    }
    


    
    public function places(Application $app)
    {
        return $app['twig']->render('site/index/places.html.twig', array(
            ));
    }
    
    
    public function currentUser(Application $app)
    {
        if ($app['currentUser']) {
            return $app['twig']->render('site/index/currentUser.user.html.twig', array(
                ));
        } else {
            return $app['twig']->render('site/index/currentUser.anon.html.twig', array(
                ));
        }
    }
}
