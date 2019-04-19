<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ConfigController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function tasks(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.tasks.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function messageQue(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.messageque.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function database(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.database.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }


    public function newSites(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.newsites.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }


    public function media(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.media.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function urls(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.urls.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function sysadminUI(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.sysadminui.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }


    public function email(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.email.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function logging(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.logging.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }


    public function externalAnalytics(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.externalanalytics.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function import(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.import.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }

    public function themes(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/config/index.themes.html.twig', array(
                'configCheck'=>new \ConfigCheck($app['config']),
            ));
    }
}
