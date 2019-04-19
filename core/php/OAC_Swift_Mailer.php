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

        // https://github.com/symfony/swiftmailer-bundle/issues/39
        // https://github.com/OpenACalendar/OpenACalendar-Web-Core/issues/767
        try {
            parent::send($message, $failedRecipients);
        } catch (Exception $e) {
            parent::getTransport()->stop();
            parent::getTransport()->start();
            parent::send($message, $failedRecipients);
        }
    }
}
