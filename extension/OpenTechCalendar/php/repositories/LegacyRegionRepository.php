<?php

namespace repositories;

use models\LegacyRegionModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class LegacyRegionRepository
{
    public function loadById($id)
    {
        global $DB;
        $stat = $DB->prepare("SELECT legacy_region_information.* FROM legacy_region_information ".
                " WHERE legacy_region_information.id =:id ");
        $stat->execute(array( 'id'=>$id));
        if ($stat->rowCount() > 0) {
            $region = new LegacyRegionModel();
            $region->setFromDataBaseRow($stat->fetch());
            return $region;
        }
    }
}
