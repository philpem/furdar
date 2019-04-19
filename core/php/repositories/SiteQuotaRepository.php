<?php


namespace repositories;

use models\SiteQuotaModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteQuotaRepository
{


    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function loadByCode(string $code)
    {
        $stat = $this->app['db']->prepare("SELECT site_quota_information.* FROM site_quota_information ".
                " WHERE site_quota_information.code =:code ");
        $stat->execute(array( 'code'=> strtoupper($code)));
        if ($stat->rowCount() > 0) {
            $siteQuota = new SiteQuotaModel();
            $siteQuota->setFromDataBaseRow($stat->fetch());
            return $siteQuota;
        }
    }
    
    public function loadById(int $id)
    {
        $stat = $this->app['db']->prepare("SELECT site_quota_information.* FROM site_quota_information ".
                " WHERE site_quota_information.id =:id ");
        $stat->execute(array( 'id'=>$id));
        if ($stat->rowCount() > 0) {
            $siteQuota = new SiteQuotaModel();
            $siteQuota->setFromDataBaseRow($stat->fetch());
            return $siteQuota;
        }
    }
}
