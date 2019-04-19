<?php

namespace sysadmin\controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use models\SiteModel;
use repositories\SiteRepository;
use Symfony\Component\Form\FormError;
use sysadmin\forms\LogInUserForm;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class LogInController
{
    public function index(Request $request, Application $app)
    {
        $form = $app['form.factory']->create(LogInUserForm::class);
        
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();
                
                if ($app['currentUser']->checkPassword($data['password']) && $data['extrapassword'] == $app['config']->sysAdminExtraPassword) {
                    $app['websession']->set('sysAdminLastActive', \TimeSource::time());
                    return $app->redirect("/sysadmin");
                } else {
                    $form->addError(new FormError('passwords wrong'));
                }
            }
        }
        
        
        return $app['twig']->render('sysadmin/login/index.html.twig', array(
            'form'=>$form->createView(),
        ));
    }
}
