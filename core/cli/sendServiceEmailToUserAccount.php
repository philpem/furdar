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

$stat = $app['db']->prepare("SELECT * FROM user_account_information WHERE id >= :from AND id <= :to AND is_closed_by_sys_admin='f' ORDER BY id ASC");
$stat->execute(array(
    'from'=>$fromID,
    'to'=>$toID,
));
while($data = $stat->fetch()) {
    $user = new \models\UserAccountModel();
    $user->setFromDataBaseRow($data);
    print "User ". $user->getId()."\n";

    $messageText = $app['twig']->render('email/serviceEmailToUserAccount.txt.twig', array(
        'user'=>$user,
    ));

    $messageHTML = $app['twig']->render('email/serviceEmailToUserAccount.html.twig', array(
        'user'=>$user,
    ));

    $message = new \Swift_Message();
    $message->setSubject("Service Email");
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
