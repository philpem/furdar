<?php
/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */


############################ OLD LEGACY

$app->match("/api1/person/{username}/ical", "index\controllers\PublicUserController::ical");
$app->match("/api1/person/{username}/ical/", "index\controllers\PublicUserController::ical");
$app->match("/api1/person/{username}/privateA/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttending");
$app->match("/api1/person/{username}/privateA/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttending");
$app->match("/api1/person/{username}/privateAW/{accesskey}/ical", "index\controllers\PrivateUserController::icalAttendingWatching");
$app->match("/api1/person/{username}/privateAW/{accesskey}/ical/", "index\controllers\PrivateUserController::icalAttendingWatching");
