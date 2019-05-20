<?php

namespace repositories;

use models\ImportModel;
use models\ImportHistoryModel;
use models\UserAccountModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportHistoryRepository
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function ensureChangedFlagsAreSet(ImportHistoryModel $importurlhistory)
    {

        
        // do we already have them?
        if (!$importurlhistory->isAnyChangeFlagsUnknown()) {
            return;
        }
        
        // load last.
        $stat = $this->app['db']->prepare("SELECT * FROM import_url_history WHERE import_url_id = :id AND created_at < :at ".
                "ORDER BY created_at DESC");
        $stat->execute(array('id'=>$importurlhistory->getId(),'at'=>$importurlhistory->getCreatedAt()->format("Y-m-d H:i:s")));
        
        
        if ($stat->rowCount() == 0) {
            $importurlhistory->setChangedFlagsFromNothing();
        } else {
            while ($importurlhistory->isAnyChangeFlagsUnknown() && $lastHistoryData = $stat->fetch()) {
                $lastHistory = new ImportHistoryModel();
                $lastHistory->setFromDataBaseRow($lastHistoryData);
                $importurlhistory->setChangedFlagsFromLast($lastHistory);
            }
        }

        // Save back to DB
        $sqlFields = array();
        $sqlParams = array(
            'id'=>$importurlhistory->getId(),
            'created_at'=>$importurlhistory->getCreatedAt()->format("Y-m-d H:i:s"),
            'is_new'=>$importurlhistory->getIsNew()?1:0,
        );

        if ($importurlhistory->getTitleChangedKnown()) {
            $sqlFields[] = " title_changed = :title_changed ";
            $sqlParams['title_changed'] = $importurlhistory->getTitleChanged() ? 1 : -1;
        }
        if ($importurlhistory->getAreaIdChangedKnown()) {
            $sqlFields[] = " area_id_changed = :area_id_changed ";
            $sqlParams['area_id_changed'] = $importurlhistory->getAreaIdChanged() ? 1 : -1;
        }
        if ($importurlhistory->getCountryIdChangedKnown()) {
            $sqlFields[] = " country_id_changed = :country_id_changed ";
            $sqlParams['country_id_changed'] = $importurlhistory->getCountryIdChanged() ? 1 : -1;
        }
        if ($importurlhistory->getIsEnabledChangedKnown()) {
            $sqlFields[] = " is_enabled_changed = :is_enabled_changed ";
            $sqlParams['is_enabled_changed'] = $importurlhistory->getIsEnabledChanged() ? 1 : -1;
        }
        if ($importurlhistory->getExpiredAtChangedKnown()) {
            $sqlFields[] = " expired_at_changed  = :expired_at_changed ";
            $sqlParams['expired_at_changed'] = $importurlhistory->getExpiredAtChanged() ? 1 : -1;
        }
        if ($importurlhistory->getGroupIdChangedKnown()) {
            $sqlFields[] = " group_id_changed = :group_id_changed ";
            $sqlParams['group_id_changed'] = $importurlhistory->getGroupIdChanged() ? 1 : -1;
        }
        if ($importurlhistory->getIsManualEventsCreationChangedKnown()) {
            $sqlFields[] = " is_manual_events_creation_changed = :is_manual_events_creation_changed ";
            $sqlParams['is_manual_events_creation_changed'] = $importurlhistory->getIsManualEventsCreationChanged() ? 1 : -1;
        }

        $statUpdate = $this->app['db']->prepare("UPDATE import_url_history SET ".
            " is_new = :is_new, ".
            implode(" , ", $sqlFields).
            " WHERE import_url_id = :id AND created_at = :created_at");
        $statUpdate->execute($sqlParams);
    }
}
