<?php
namespace incominglinks;

use models\SiteModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class WebMentionIncomingLink extends \BaseIncomingLink
{
    public static function receive(Application $app, SiteModel $siteModel = null)
    {
        $data = array_merge($_POST, $_GET);

        if (isset($data['source']) && isset($data['target'])) {
            $pbil = new \incominglinks\WebMentionIncomingLink();
            $pbil->setSourceURL($data['source']);
            $pbil->setTargetURL($data['target']);
            $pbil->setReporterIp($_SERVER['REMOTE_ADDR']);
            $pbil->setReporterUseragent($_SERVER['HTTP_USER_AGENT']);

            $repo = new \repositories\IncomingLinkRepository($app);
            $repo->create($pbil, $siteModel);

            header("HTTP/1.0 202 ");

            print "WebMention Received";
        } else {

            // TODO
        }
    }






    public function getType()
    {
        return "WEBMENTION";
    }
}
