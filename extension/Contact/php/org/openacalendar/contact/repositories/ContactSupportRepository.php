<?php


namespace org\openacalendar\contact\repositories;

use org\openacalendar\contact\models\ContactSupportModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ContactSupportRepository
{
    public function create(ContactSupportModel $contact)
    {
        global $DB;
        
        
        $stat = $DB->prepare("INSERT INTO contact_support (subject, message, email, user_account_id, ip, browser, created_at, is_spam_honeypot_field_detected) ".
                "VALUES (:subject, :message, :email, :user_account_id, :ip, :browser, :created_at, :is_spam_honeypot_field_detected) RETURNING id");
        $stat->execute(array(
                'subject'=>substr($contact->getSubject(), 0, VARCHAR_COLUMN_LENGTH_USED),
                'message'=>$contact->getMessage(),
                'email'=>substr($contact->getEmail(), 0, VARCHAR_COLUMN_LENGTH_USED),
                'user_account_id'=> $contact->getUserAccountId(),
                'ip'=>substr($contact->getIp(), 0, VARCHAR_COLUMN_LENGTH_USED),
                'browser'=>  $contact->getBrowser(),
                'created_at'=> \TimeSource::getFormattedForDataBase(),
                'is_spam_honeypot_field_detected'=>  $contact->getIsSpamHoneypotFieldDetected()?1:0,
            ));
        $data = $stat->fetch();
        $contact->setId($data['id']);
    }
    
    public function loadById($id)
    {
        global $DB;
        $stat = $DB->prepare("SELECT contact_support.* FROM contact_support ".
                " WHERE contact_support.id =:id ");
        $stat->execute(array( 'id'=>$id));
        if ($stat->rowCount() > 0) {
            $cs = new ContactSupportModel();
            $cs->setFromDataBaseRow($stat->fetch());
            return $cs;
        }
    }
}
