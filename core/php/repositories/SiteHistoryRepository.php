<?php

namespace repositories;

use models\SiteModel;
use models\SiteHistoryModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteHistoryRepository
{


    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function ensureChangedFlagsAreSet(SiteHistoryModel $sitehistory)
    {

        
        // do we already have them?
        if (!$sitehistory->isAnyChangeFlagsUnknown()) {
            return;
        }
        
        // load last.
        $stat = $this->app['db']->prepare("SELECT * FROM site_history WHERE site_id = :id AND created_at < :at ".
                "ORDER BY created_at DESC");
        $stat->execute(array('id'=>$sitehistory->getId(),'at'=>$sitehistory->getCreatedAt()->format("Y-m-d H:i:s")));
        
        
        if ($stat->rowCount() == 0) {
            $sitehistory->setChangedFlagsFromNothing();
        } else {
            while ($sitehistory->isAnyChangeFlagsUnknown() && $lastHistoryData = $stat->fetch()) {
                $lastHistory = new SiteHistoryModel();
                $lastHistory->setFromDataBaseRow($lastHistoryData);
                $sitehistory->setChangedFlagsFromLast($lastHistory);
            }
        }




        // Save back to DB
        $sqlFields = array();
        $sqlParams = array(
            'id'=>$sitehistory->getId(),
            'created_at'=>$sitehistory->getCreatedAt()->format("Y-m-d H:i:s"),
            'is_new'=>$sitehistory->getIsNew()?1:0,
        );


        if ($sitehistory->getTitleChangedKnown()) {
            $sqlFields[] = " title_changed = :title_changed ";
            $sqlParams['title_changed'] = $sitehistory->getTitleChanged() ? 1 : -1;
        }
        if ($sitehistory->getSlugChangedKnown()) {
            $sqlFields[] = " slug_changed = :slug_changed ";
            $sqlParams['slug_changed'] = $sitehistory->getSlugChanged() ? 1 : -1;
        }
        if ($sitehistory->getDescriptionTextChangedKnown()) {
            $sqlFields[] = " description_text_changed = :description_text_changed ";
            $sqlParams['description_text_changed'] = $sitehistory->getDescriptionTextChanged() ? 1 : -1;
        }
        if ($sitehistory->getFooterTextChangedKnown()) {
            $sqlFields[] = " footer_text_changed = :footer_text_changed ";
            $sqlParams['footer_text_changed'] = $sitehistory->getFooterTextChanged() ? 1 : -1;
        }
        if ($sitehistory->getIsWebRobotsAllowedChangedKnown()) {
            $sqlFields[] = " is_web_robots_allowed_changed = :is_web_robots_allowed_changed ";
            $sqlParams['is_web_robots_allowed_changed'] = $sitehistory->getIsWebRobotsAllowedChanged() ? 1 : -1;
        }
        if ($sitehistory->getIsClosedBySysAdminChangedKnown()) {
            $sqlFields[] = " is_closed_by_sys_admin_changed = :is_closed_by_sys_admin_changed ";
            $sqlParams['is_closed_by_sys_admin_changed'] = $sitehistory->getIsClosedBySysAdminChanged() ? 1 : -1;
        }
        if ($sitehistory->getClosedBySyAdminReasonChangedKnown()) {
            $sqlFields[] = " closed_by_sys_admin_reason_changed = :closed_by_sys_admin_reason_changed ";
            $sqlParams['closed_by_sys_admin_reason_changed'] = $sitehistory->getClosedBySyAdminReasonChanged() ? 1 : -1;
        }
        if ($sitehistory->getIsListedInIndexChangedKnown()) {
            $sqlFields[] = " is_listed_in_index_changed = :is_listed_in_index_changed ";
            $sqlParams['is_listed_in_index_changed'] = $sitehistory->getIsListedInIndexChanged() ? 1 : -1;
        }
        if ($sitehistory->getPromptEmailsDaysInAdvanceChangedKnown()) {
            $sqlFields[] = " prompt_emails_days_in_advance_changed = :prompt_emails_days_in_advance_changed ";
            $sqlParams['prompt_emails_days_in_advance_changed'] = $sitehistory->getPromptEmailsDaysInAdvanceChanged() ? 1 : -1;
        }

        $statUpdate = $this->app['db']->prepare("UPDATE site_history SET ".
            " is_new = :is_new, ".
            implode(" , ", $sqlFields).
            " WHERE site_id = :id AND created_at = :created_at");
        $statUpdate->execute($sqlParams);
    }
}
