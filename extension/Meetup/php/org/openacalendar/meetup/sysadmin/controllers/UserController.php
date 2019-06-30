<?php

namespace org\openacalendar\meetup\sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class UserController
{
    public function index(Request $request, Application $app)
    {
        $extension = $app['extensions']->getExtensionById('org.openacalendar.meetup');
        $oauthKey =  $app['appconfig']->getValue($extension->getAppConfigurationDefinition('oauth_key'));
        $oauthSecret =  $app['appconfig']->getValue($extension->getAppConfigurationDefinition('oauth_secret'));
        $accessToken =  $app['appconfig']->getValue($extension->getAppConfigurationDefinition('access_token'));
        $refreshToken =  $app['appconfig']->getValue($extension->getAppConfigurationDefinition('refresh_token'));

        if ('POST' == $request->getMethod() && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()
            && $request->request->get('submitted') == 'appdetails') {
            $oauthKey = $request->request->get('oauth_key');
            $oauthSecret = $request->request->get('oauth_secret');

            $app['appconfig']->setValue($extension->getAppConfigurationDefinition('oauth_key'), $oauthKey);
            $app['appconfig']->setValue($extension->getAppConfigurationDefinition('oauth_secret'), $oauthSecret);
        }

        if (!$app['websession']->get('meetup-oauth-signin-state')) {
            $app['websession']->set('meetup-oauth-signin-state', createKey(10, 40));
        }

        return $app['twig']->render('meetup/sysadmin/user/index.html.twig', array(
            'oauth_key'=>$oauthKey,
            'oauth_secret'=>($oauthSecret?substr($oauthSecret, 0, 1)."XXXXXXX":''),
            'access_token'=>($accessToken?substr($accessToken, 0, 1)."XXXXXXX":''),
            'refresh_token'=>($refreshToken?substr($refreshToken, 0, 1)."XXXXXXX":''),
            'state'=>$app['websession']->get('meetup-oauth-signin-state'),
        ));
    }
}
