<?php

namespace repositories\builders;

use models\SiteQuotaModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteQuotaRepositoryBuilder extends BaseRepositoryBuilder
{
    protected function build()
    {
        $this->select[] = 'site_quota_information.*';
    }
    
    protected function buildStat()
    {
        $sql = "SELECT ".  implode(",", $this->select)." FROM site_quota_information ".
                implode(" ", $this->joins).
                ($this->where ? " WHERE ".implode(" AND ", $this->where) : "").
                " ORDER BY site_quota_information.id ASC ".($this->limit > 0 ? " LIMIT ". $this->limit : "");
            
        $this->stat = $this->app['db']->prepare($sql);
        $this->stat->execute($this->params);
    }
    
    
    public function fetchAll()
    {
        $this->buildStart();
        $this->build();
        $this->buildStat();
        
        
        
        $results = array();
        while ($data = $this->stat->fetch()) {
            $siteQuota = new SiteQuotaModel();
            $siteQuota->setFromDataBaseRow($data);
            $results[] = $siteQuota;
        }
        return $results;
    }
}
