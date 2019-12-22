<?php
define('APP_ROOT_DIR', __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require_once(defined('COMPOSER_ROOT_DIR') ? COMPOSER_ROOT_DIR : APP_ROOT_DIR).'/vendor/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoload.php';
require_once APP_ROOT_DIR.'/core/php/autoloadCLI.php';

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

$email = $argv[1];
$sleep = intval($argv[2]);
if (!$email || !$sleep) {
    print "MUST SET EMAIL AND SLEEP\n\n";
    exit;
}

configureAppForThemeVariables(null);

# As well as monolog, we make sure we write our own log
# This is because by default monolog is not configured to log data at this level and we want to make sure we have a log
$file = fopen("continuousSendEmail.log", "a");

while(true) {
    $app['monolog']->addWarning("Sending Test Email To  ".$email);
    fwrite($file, date("c"). " ".$email.PHP_EOL);

    $messageText = "This is a test message";
    $messageHTML = "<b>This is a test message</b>";

    $message = new \Swift_Message();
    $message->setSubject("continuousSendEmail");
    $message->setFrom(array($app['config']->emailFrom => $app['config']->emailFromName));
    $message->setTo($email);
    $message->setBody($messageText);
    $message->addPart($messageHTML, 'text/html');

    if ($app['config']->actuallySendEmail) {
        print "Sending to ". $email."\n";
        $numSent=$app['mailer']->send($message);
        if($numSent) {
            printf("Sent %d messages to %s\n", $numSent, $email);
        } else {
            print "FAILED TO SEND\n";
        }
    }

    sleep($sleep);
}
print "Done\n\n";
