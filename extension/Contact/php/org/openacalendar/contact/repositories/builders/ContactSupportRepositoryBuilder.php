<?php

namespace org\openacalendar\contact\repositories\builders;

use org\openacalendar\contact\models\ContactSupportModel;
use repositories\builders\BaseRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ContactSupportRepositoryBuilder extends BaseRepositoryBuilder
{
    protected function build()
    {
        $this->select[]  = ' contact_support.* ';
    }
    
    protected function buildStat()
    {
        global $DB;
        
    
        
        
        
        $sql = "SELECT ".  implode(",", $this->select)." FROM contact_support ".
                implode(" ", $this->joins).
                ($this->where ? " WHERE ".implode(" AND ", $this->where) : '').
                " ORDER BY contact_support.id ASC ";
    
        $this->stat = $DB->prepare($sql);
        $this->stat->execute($this->params);
    }
    
    
    public function fetchAll()
    {
        $this->buildStart();
        $this->build();
        $this->buildStat();
        
        
        $results = array();
        while ($data = $this->stat->fetch()) {
            $cList = new ContactSupportModel();
            $cList->setFromDataBaseRow($data);
            $results[] = $cList;
        }
        return $results;
    }
}
