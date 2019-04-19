<?php

namespace org\openacalendar\contact\sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use org\openacalendar\contact\repositories\builders\ContactSupportRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ContactSupportListController
{
    public function index(Request $request, Application $app)
    {
        $csrb = new ContactSupportRepositoryBuilder($app);
        
        return $app['twig']->render('sysadmin/contactsupportlist/index.html.twig', array(
                'contactsupports'=>$csrb->fetchAll(),
            ));
    }
}
