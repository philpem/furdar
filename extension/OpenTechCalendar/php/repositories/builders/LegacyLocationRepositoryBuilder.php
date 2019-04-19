<?php

namespace repositories\builders;

use models\LegacyLocationModel;
use models\LegacyRegionModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class LegacyLocationRepositoryBuilder
{
    protected $region;
    
    public function setLegacyRegion(LegacyRegionModel $region)
    {
        $this->region = $region;
    }

    



    public function fetchAll()
    {
        global $DB;
        
        $where = array();
        $joins = array();
        $params = array();
        $select = array('legacy_location_information.*');
        
        if ($this->region) {
            $where[] = " legacy_location_information.legacy_region_id = :legacy_region_id  ";
            $params['legacy_region_id'] = $this->region->getId();
        }
        
        
        $sql = "SELECT ".  implode(",", $select)." FROM legacy_location_information ".
                implode(" ", $joins).
                ($where ? " WHERE ".implode(" AND ", $where) : "").
                " ORDER BY legacy_location_information.title ASC ";
            
        $stat = $DB->prepare($sql);
        $stat->execute($params);
        
        $results = array();
        while ($data = $stat->fetch()) {
            $location = new LegacyLocationModel();
            $location->setFromDataBaseRow($data);
            $results[] = $location;
        }
        return $results;
    }
}
