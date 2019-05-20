<?php

namespace repositories\builders;

use models\SiteModel;
use models\UserWatchesSiteModel;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSiteRepositoryBuilder extends BaseRepositoryBuilder
{
    
    
    /** @var SiteModel **/
    protected $site;

    public function setSite(SiteModel $site)
    {
        $this->site = $site;
        return $this;
    }
    
    protected $onlyCurrent = true;

    protected function build()
    {
        if ($this->onlyCurrent) {
            $this->where[] = "user_watches_site_information.is_watching = '1'";
        }
        
        if ($this->site) {
            $this->where[] = " user_watches_site_information.site_id = :site_id";
            $this->params['site_id'] = $this->site->getId();
        }
    }
    
    protected function buildStat()
    {
        $sql = "SELECT user_watches_site_information.* FROM user_watches_site_information ".
                implode(" ", $this->joins).
                ($this->where ? " WHERE ".implode(" AND ", $this->where) : "").
                ($this->limit > 0 ? " LIMIT ". $this->limit : "");
    
        
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
            $uwsm = new UserWatchesSiteModel();
            $uwsm->setFromDataBaseRow($data);
            $results[] = $uwsm;
        }
        return $results;
    }
}
