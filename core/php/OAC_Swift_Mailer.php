<?php

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class OAC_Swift_Mailer extends Swift_Mailer
{
    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {

        /* Swift_Mailer has known issues with dropped SMTP connections. This is not usually a problem 
        ** except for long lived cases when sending through very fussy SMTP gateways such as AWS SES.
        ** To mitigate this is is advisable to stop the connection after each send. Swift_Mailer will 
        ** automatically re-establish the connection on the next send. This has a performance 
        ** hit if you're sending a LOT of emails, but we won't notice much difference for this app.
        */

        $result=parent::send($message, $failedRecipients);
        parent::getTransport()->stop();
        return $result;
    }
}
