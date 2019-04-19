<?php

/**
 *
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

$app->match('/newGist', "org\openacalendar\gists\site\controllers\GistNewController::index");

$app->match('/gist/{slug}', "org\openacalendar\gists\site\controllers\GistController::index");
$app->match('/gist/{slug}/', "org\openacalendar\gists\site\controllers\GistController::index");

$app->match('/gist/{slug}/edit', "org\openacalendar\gists\site\controllers\GistEditController::index");

$app->match('/gist/{slug}/edit/content/new', "org\openacalendar\gists\site\controllers\GistEditController::newContent");
