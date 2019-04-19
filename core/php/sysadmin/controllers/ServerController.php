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
class ServerController
{
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('sysadmin/server/index.html.twig', array(
            ));
    }
    
    
    public function phpinfo(Request $request, Application $app)
    {
        phpinfo();
        
        // now return a space, so silex thinks there is some output to send.
        return " ";
    }
}
