<?php

namespace org\openacalendar\contact\index\controllers;

use org\openacalendar\contact\index\forms\ContactForm;
use org\openacalendar\contact\models\ContactSupportModel;
use org\openacalendar\contact\repositories\ContactSupportRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IndexController
{
    public function contact(Application $app, Request $request)
    {
        $form = $app['form.factory']->create(ContactForm::class, null, array('config'=>$app['config'], 'user' => $app['currentUser']));
        
        if (!$app['config']->siteReadOnly && 'POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $contact = new ContactSupportModel();
                $contact->setSubject($data['subject']);
                $contact->setMessage($data['message']);
                $contact->setEmail($data['email']);
                if ($app['currentUser']) {
                    $contact->setUserAccountId($app['currentUser']->getId());
                }
                $contact->setIp($_SERVER['REMOTE_ADDR']);
                $contact->setBrowser($_SERVER['HTTP_USER_AGENT']);
                if ($request->request->get('url')) {
                    $contact->setIsSpamHoneypotFieldDetected(true);
                }

                $contactSupportRepository = new ContactSupportRepository();
                $contactSupportRepository->create($contact);

                if (!$contact->getIsSpam()) {
                    $contact->sendEmailToSupport($app, $app['currentUser']);
                }

                $app['flashmessages']->addMessage('Your message has been sent');
                return $app->redirect('/contact');
            }
        }
        
        return $app['twig']->render('index/index/contact.html.twig', array(
                'form'=>$form->createView(),
            ));
    }
}
