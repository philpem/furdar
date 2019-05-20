<?php

namespace repositories;

use models\VenueModel;
use models\VenueHistoryModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueHistoryRepository
{


    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function ensureChangedFlagsAreSet(VenueHistoryModel $venuehistory)
    {

        
        // do we already have them?
        if (!$venuehistory->isAnyChangeFlagsUnknown()) {
            return;
        }
        
        // load last.
        $stat = $this->app['db']->prepare("SELECT * FROM venue_history WHERE venue_id = :id AND created_at < :at ".
                "ORDER BY created_at DESC");
        $stat->execute(array('id'=>$venuehistory->getId(),'at'=>$venuehistory->getCreatedAt()->format("Y-m-d H:i:s")));

        // Apply what we know!
        if ($stat->rowCount() == 0) {
            $venuehistory->setChangedFlagsFromNothing();
        } else {
            while ($venuehistory->isAnyChangeFlagsUnknown() && $lastHistoryData = $stat->fetch()) {
                $lastHistory = new VenueHistoryModel();
                $lastHistory->setFromDataBaseRow($lastHistoryData);
                $venuehistory->setChangedFlagsFromLast($lastHistory);
            }
        }

        // Save back to DB
        $sqlFields = array();
        $sqlParams = array(
            'id'=>$venuehistory->getId(),
            'created_at'=>$venuehistory->getCreatedAt()->format("Y-m-d H:i:s"),
            'is_new'=>$venuehistory->getIsNew()?1:0,
        );

        if ($venuehistory->getTitleChangedKnown()) {
            $sqlFields[] = " title_changed = :title_changed ";
            $sqlParams['title_changed'] = $venuehistory->getTitleChanged() ? 1 : -1;
        }
        if ($venuehistory->getDescriptionChangedKnown()) {
            $sqlFields[] = " description_changed = :description_changed ";
            $sqlParams['description_changed'] = $venuehistory->getDescriptionChanged() ? 1 : -1;
        }
        if ($venuehistory->getLatChangedKnown()) {
            $sqlFields[] = " lat_changed = :lat_changed ";
            $sqlParams['lat_changed'] = $venuehistory->getLatChanged() ? 1 : -1;
        }
        if ($venuehistory->getLngChangedKnown()) {
            $sqlFields[] = " lng_changed = :lng_changed ";
            $sqlParams['lng_changed'] = $venuehistory->getLngChanged() ? 1 : -1;
        }
        if ($venuehistory->getCountryIdChangedKnown()) {
            $sqlFields[] = " country_id_changed = :country_id_changed ";
            $sqlParams['country_id_changed'] = $venuehistory->getCountryIdChanged() ? 1 : -1;
        }
        if ($venuehistory->getAreaIdChangedKnown()) {
            $sqlFields[] = " area_id_changed = :area_id_changed ";
            $sqlParams['area_id_changed'] = $venuehistory->getAreaIdChanged() ? 1 : -1;
        }
        if ($venuehistory->getAddressChangedKnown()) {
            $sqlFields[] = " address_changed = :address_changed ";
            $sqlParams['address_changed'] = $venuehistory->getAddressChanged() ? 1 : -1;
        }
        if ($venuehistory->getAddressCodeChangedKnown()) {
            $sqlFields[] = " address_code_changed = :address_code_changed ";
            $sqlParams['address_code_changed'] = $venuehistory->getAddressCodeChanged() ? 1 : -1;
        }
        if ($venuehistory->getIsDuplicateOfIdChangedKnown()) {
            $sqlFields[] = " is_duplicate_of_id_changed  = :is_duplicate_of_id_changed ";
            $sqlParams['is_duplicate_of_id_changed'] = $venuehistory->getIsDuplicateOfIdChangedKnown() ? 1 : -1;
        }
        if ($venuehistory->getIsDeletedChangedKnown()) {
            $sqlFields[] = " is_deleted_changed = :is_deleted_changed ";
            $sqlParams['is_deleted_changed'] = $venuehistory->getIsDeletedChanged() ? 1 : -1;
        }

        $statUpdate = $this->app['db']->prepare("UPDATE venue_history SET ".
                " is_new = :is_new, ".
                implode(" , ", $sqlFields).
                " WHERE venue_id = :id AND created_at = :created_at");
        $statUpdate->execute($sqlParams);
    }
}
