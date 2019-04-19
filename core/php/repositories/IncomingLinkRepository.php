<?php


namespace repositories;

use models\SiteModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IncomingLinkRepository
{


    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function create(\BaseIncomingLink $incomingLink, SiteModel $site=null)
    {
        try {
            $this->app['db']->beginTransaction();

            $stat = $this->app['db']->prepare("INSERT INTO incoming_link (site_id, extension_id, type, source_url, target_url, reporter_useragent, reporter_ip, created_at) ".
                    "VALUES (:site_id, :extension_id, :type, :source_url, :target_url, :reporter_useragent, :reporter_ip, :created_at) RETURNING id");
            $stat->execute(array(
                    'site_id'=>($site ? $site->getId() : null),
                    'extension_id'=>$incomingLink->getTypeExtensionID(),
                    'type'=>$incomingLink->getType(),
                    'source_url'=>$incomingLink->getSourceURL(),
                    'target_url'=>$incomingLink->getTargetURL(),
                    'reporter_useragent'=>$incomingLink->getReporterUseragent(),
                    'reporter_ip'=>$incomingLink->getReporterIp(),
                    'created_at'=>$this->app['timesource']->getFormattedForDataBase(),
                ));
            $data = $stat->fetch();
            $incomingLink->setId($data['id']);

            $this->app['db']->commit();
        } catch (Exception $e) {
            $this->app['db']->rollBack();
        }
    }
}
