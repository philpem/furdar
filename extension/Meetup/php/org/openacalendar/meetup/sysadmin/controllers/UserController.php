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
        $appKey = $app['appconfig']->getValue($extension->getAppConfigurationDefinition('app_key'));
        
        if ('POST' == $request->getMethod() && $request->request->get('CSFRToken') == $app['websession']->getCSFRToken()
            && $request->request->get('submitted') == 'appdetails') {
            $appKey = $request->request->get('app_key');
            
            $app['appconfig']->setValue($extension->getAppConfigurationDefinition('app_key'), $appKey);
        }
        return $app['twig']->render('meetup/sysadmin/user/index.html.twig', array(
            'app_key'=>($appKey?substr($appKey, 0, 3)."XXXXXXX":''),
        ));
    }
}
