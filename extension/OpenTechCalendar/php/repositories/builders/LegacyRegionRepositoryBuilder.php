<?php

namespace repositories\builders;

use models\LegacyRegionModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class LegacyRegionRepositoryBuilder
{
    public function fetchAll()
    {
        global $DB;
        
        $where = array();
        $joins = array();
        $params = array();
        $select = array('legacy_region_information.*');
        
        
        
        $sql = "SELECT ".  implode(",", $select)." FROM legacy_region_information ".
                implode(" ", $joins).
                ($where ? " WHERE ".implode(" AND ", $where) : "").
                " ORDER BY legacy_region_information.title ASC ";
            
        $stat = $DB->prepare($sql);
        $stat->execute($params);
        
        $results = array();
        while ($data = $stat->fetch()) {
            $region = new LegacyRegionModel();
            $region->setFromDataBaseRow($data);
            $results[] = $region;
        }
        return $results;
    }
}
