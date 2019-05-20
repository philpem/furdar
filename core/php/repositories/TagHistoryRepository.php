<?php

namespace repositories;

use models\TagModel;
use models\TagHistoryModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TagHistoryRepository
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    public function ensureChangedFlagsAreSet(TagHistoryModel $tagHistory)
    {


        // do we already have them?
        if (!$tagHistory->isAnyChangeFlagsUnknown()) {
            return;
        }

        // load last.
        $stat = $this->app['db']->prepare("SELECT * FROM tag_history WHERE tag_id = :id AND created_at < :at ".
            "ORDER BY created_at DESC");
        $stat->execute(array('id'=>$tagHistory->getId(),'at'=>$tagHistory->getCreatedAt()->format("Y-m-d H:i:s")));


        // Apply what we know
        if ($stat->rowCount() == 0) {
            $tagHistory->setChangedFlagsFromNothing();
        } else {
            while ($tagHistory->isAnyChangeFlagsUnknown() && $lastHistoryData = $stat->fetch()) {
                $lastHistory = new TagHistoryModel();
                $lastHistory->setFromDataBaseRow($lastHistoryData);
                $tagHistory->setChangedFlagsFromLast($lastHistory);
            }
        }

        // Save back to DB
        $sqlFields = array();
        $sqlParams = array(
            'id'=>$tagHistory->getId(),
            'created_at'=>$tagHistory->getCreatedAt()->format("Y-m-d H:i:s"),
            'is_new'=>$tagHistory->getIsNew()?1:0,
        );

        if ($tagHistory->getTitleChangedKnown()) {
            $sqlFields[] = " title_changed = :title_changed ";
            $sqlParams['title_changed'] = $tagHistory->getTitleChanged() ? 1 : -1;
        }
        if ($tagHistory->getDescriptionChangedKnown()) {
            $sqlFields[] = " description_changed = :description_changed ";
            $sqlParams['description_changed'] = $tagHistory->getDescriptionChanged() ? 1 : -1;
        }
        if ($tagHistory->getIsDeletedChangedKnown()) {
            $sqlFields[] = " is_deleted_changed = :is_deleted_changed ";
            $sqlParams['is_deleted_changed'] = $tagHistory->getIsDeletedChanged() ? 1 : -1;
        }
        $statUpdate = $this->app['db']->prepare("UPDATE tag_history SET ".
            " is_new = :is_new, ".implode(" , ", $sqlFields).
            " WHERE tag_id = :id AND created_at = :created_at");
        $statUpdate->execute($sqlParams);
    }
}
