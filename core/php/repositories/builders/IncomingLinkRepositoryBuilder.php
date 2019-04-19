<?php

namespace repositories\builders;

use incominglinks\PingBackIncomingLink;
use incominglinks\WebMentionIncomingLink;
use models\SiteModel;
use models\TagModel;
use models\EventModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class IncomingLinkRepositoryBuilder extends BaseRepositoryBuilder
{
    

    /** @var SiteModel **/
    protected $site;
    
    public function setSite(SiteModel $site)
    {
        $this->site = $site;
    }


    protected function build()
    {
        if ($this->site) {
            $this->where[] =  " incoming_link.site_id = :site_id ";
            $this->params['site_id'] = $this->site->getId();
        }
    }
    
    protected function buildStat()
    {
        $sql = "SELECT incoming_link.* FROM incoming_link ".
                implode(" ", $this->joins).
                ($this->where?" WHERE ".implode(" AND ", $this->where):"").
                " ORDER BY incoming_link.created_at ASC  ".($this->limit > 0 ? " LIMIT ". $this->limit : "");
    
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
            // TODO should be got from extensions properly!
            if ($data['type'] == 'PINGBACK') {
                $il = new PingBackIncomingLink();
            } else {
                $il = new WebMentionIncomingLink();
            }
            $il->setFromDataBaseRow($data);
            $results[] = $il;
        }
        return $results;
    }
}
