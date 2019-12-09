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

$fromID = intval($argv[1]);
$toID = intval($argv[2]);
$sleep = intval($argv[3]);
if (!$fromID || !$toID || !$sleep) {
    print "MUST SET FROM AND TO AND SLEEP\n\n";
    exit;
}

configureAppForThemeVariables(null);

# As well as monolog, we make sure we write our own log
# This is because by default monolog is not configured to log data at this level and we want to make sure we have a log
$file = fopen("sendServiceEmailToUserAccount.log", "a");
$stat = $app['db']->prepare("SELECT * FROM user_account_information WHERE id >= :from AND id <= :to AND is_closed_by_sys_admin='f' ORDER BY id ASC");
$stat->execute(array(
    'from'=>$fromID,
    'to'=>$toID,
));
while($data = $stat->fetch()) {
    $user = new \models\UserAccountModel();
    $user->setFromDataBaseRow($data);
    print "User ". $user->getId()."\n";
    $app['monolog']->addWarning("Sending Service Email To User ".$user->getId());
    fwrite($file, date("c"). " ".$user->getId().PHP_EOL);

    $messageText = $app['twig']->render('email/serviceEmailToUserAccount.txt.twig', array(
        'user'=>$user,
    ));

    $messageHTML = $app['twig']->render('email/serviceEmailToUserAccount.html.twig', array(
        'user'=>$user,
    ));

    $message = new \Swift_Message();
    $message->setSubject("Transfer to Sheffield Digital now complete");
    $message->setFrom(array($app['config']->emailFrom => $app['config']->emailFromName));
    $message->setTo($user->getEmail());
    $message->setBody($messageText);
    $message->addPart($messageHTML, 'text/html');

    if ($app['config']->actuallySendEmail) {
        $app['mailer']->send($message);
    }

    sleep($sleep);
}
print "Done\n\n";
