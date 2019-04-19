<?php


/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


$app->match('/sysadmin/contactsupport', 'org\openacalendar\contact\sysadmin\controllers\ContactSupportListController::index');
$app->match('/sysadmin/contactsupport/', 'org\openacalendar\contact\sysadmin\controllers\ContactSupportListController::index');

$app->match('/sysadmin/contactsupport/{id}/', 'org\openacalendar\contact\sysadmin\controllers\ContactSupportController::index')
        ->assert('id', '\d+');
