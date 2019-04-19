<?php

namespace org\openacalendar\contact\sysadmin\controllers;

use Silex\Application;
use site\forms\NewEventForm;
use Symfony\Component\HttpFoundation\Request;
use repositories\UserAccountRepository;
use org\openacalendar\contact\repositories\ContactSupportRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ContactSupportController
{
    protected $parameters = array();
    
    protected function build($id, Request $request, Application $app)
    {
        $this->parameters = array('user'=>null);

        $csr = new ContactSupportRepository();
        $this->parameters['contactsupport'] = $csr->loadById($id);
        if (!$this->parameters['contactsupport']) {
            $app->abort(404);
        }
        
        if ($this->parameters['contactsupport']->getUserAccountId()) {
            $ur = new UserAccountRepository($app);
            $this->parameters['user'] = $ur->loadByID($this->parameters['contactsupport']->getUserAccountId());
        }
    }
    
    public function index($id, Request $request, Application $app)
    {
        $this->build($id, $request, $app);
        
            
        
        return $app['twig']->render('sysadmin/contactsupport/index.html.twig', $this->parameters);
    }
}
