<?php

namespace repositories;

use models\LegacyLocationModel;

/**
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class LegacyLocationRepository
{
    public function loadById($id)
    {
        global $DB;
        $stat = $DB->prepare("SELECT legacy_location_information.* FROM legacy_location_information ".
                " WHERE legacy_location_information.id =:id ");
        $stat->execute(array( 'id'=>$id));
        if ($stat->rowCount() > 0) {
            $location = new LegacyLocationModel();
            $location->setFromDataBaseRow($stat->fetch());
            return $location;
        }
    }

    public function editCache(LegacyLocationModel $location)
    {
        global $DB;
        $stat = $DB->prepare("UPDATE legacy_location_information SET cached_future_events=:cfe".
                " WHERE legacy_location_information.id =:id ");
        $stat->execute(array( 'id'=>$location->getId(), 'cfe'=>$location->getCachedFutureEvents()));
    }
}
