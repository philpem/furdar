<?php

namespace repositories;

use models\GroupModel;
use models\GroupHistoryModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupHistoryRepository
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    
    public function ensureChangedFlagsAreSet(GroupHistoryModel $groupHistory)
    {

        // do we already have them?
        if (!$groupHistory->isAnyChangeFlagsUnknown()) {
            return;
        }
        
        // load last.
        $stat = $this->app['db']->prepare("SELECT * FROM group_history WHERE group_id = :id AND created_at < :at ".
                "ORDER BY created_at DESC");
        $stat->execute(array('id'=>$groupHistory->getId(),'at'=>$groupHistory->getCreatedAt()->format("Y-m-d H:i:s")));
        
        
        if ($stat->rowCount() == 0) {
            $groupHistory->setChangedFlagsFromNothing();
        } else {
            while ($groupHistory->isAnyChangeFlagsUnknown() && $lastHistoryData = $stat->fetch()) {
                $lastHistory = new GroupHistoryModel();
                $lastHistory->setFromDataBaseRow($lastHistoryData);
                $groupHistory->setChangedFlagsFromLast($lastHistory);
            }
        }


        // Save back to DB
        $sqlFields = array();
        $sqlParams = array(
            'id'=>$groupHistory->getId(),
            'created_at'=>$groupHistory->getCreatedAt()->format("Y-m-d H:i:s"),
            'is_new'=>$groupHistory->getIsNew()?1:0,
        );

        if ($groupHistory->getTitleChangedKnown()) {
            $sqlFields[] = " title_changed = :title_changed ";
            $sqlParams['title_changed'] = $groupHistory->getTitleChanged() ? 1 : -1;
        }
        if ($groupHistory->getDescriptionChangedKnown()) {
            $sqlFields[] = " description_changed = :description_changed ";
            $sqlParams['description_changed'] = $groupHistory->getDescriptionChanged() ? 1 : -1;
        }
        if ($groupHistory->getUrlChangedKnown()) {
            $sqlFields[] = " url_changed = :url_changed ";
            $sqlParams['url_changed'] = $groupHistory->getUrlChanged() ? 1 : -1;
        }
        if ($groupHistory->getTwitterUsernameChangedKnown()) {
            $sqlFields[] = " twitter_username_changed = :twitter_username_changed ";
            $sqlParams['twitter_username_changed'] = $groupHistory->getTwitterUsernameChanged() ? 1 : -1;
        }
        if ($groupHistory->getIsDuplicateOfIdChangedKnown()) {
            $sqlFields[] = " is_duplicate_of_id_changed  = :is_duplicate_of_id_changed ";
            $sqlParams['is_duplicate_of_id_changed'] = $groupHistory->getIsDuplicateOfIdChangedKnown() ? 1 : -1;
        }
        if ($groupHistory->getIsDeletedChangedKnown()) {
            $sqlFields[] = " is_deleted_changed = :is_deleted_changed ";
            $sqlParams['is_deleted_changed'] = $groupHistory->getIsDeletedChanged() ? 1 : -1;
        }

        $statUpdate = $this->app['db']->prepare("UPDATE group_history SET ".
            " is_new = :is_new, ".
            implode(" , ", $sqlFields).
            " WHERE group_id = :id AND created_at = :created_at");
        $statUpdate->execute($sqlParams);
    }
}
