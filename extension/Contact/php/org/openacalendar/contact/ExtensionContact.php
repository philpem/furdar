<?php

namespace org\openacalendar\contact;

use models\UserAccountModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ExtensionContact extends \BaseExtension
{
    public function getId()
    {
        return 'org.openacalendar.contact';
    }

    public function getTitle()
    {
        return "Contact";
    }

    public function getDescription()
    {
        return "Contact Us Pages";
    }

    public function getSysAdminLinks()
    {
        return array(array('title'=>'Contact Support','url'=>'/sysadmin/contactsupport'));
    }

    public function purgeUser(UserAccountModel $userAccountModel)
    {
        $stat = $this->app['db']->prepare("DELETE FROM contact_support ".
            "WHERE contact_support.user_account_id =:id");
        $stat->execute(array( 'id'=>$userAccountModel->getId() ));
    }

}
