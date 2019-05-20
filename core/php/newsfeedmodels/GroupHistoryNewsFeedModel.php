<?php

namespace newsfeedmodels;

use models\GroupHistoryModel;
use models\SiteModel;

/**
     *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
     */

class GroupHistoryNewsFeedModel implements \InterfaceNewsFeedModel
{



    /** @var SiteModel */
    protected $siteModel;


    /** @var GroupHistoryModel */
    protected $groupHistoryModel;

    public function __construct($groupHistoryModel, SiteModel $siteModel)
    {
        $this->groupHistoryModel = $groupHistoryModel;
        $this->siteModel = $siteModel;
    }


    /** @return \DateTime */
    public function getCreatedAt()
    {
        return $this->groupHistoryModel->getCreatedAt();
    }

    public function getID()
    {
        // For ID, must make sure we use Slug, not SlugForURL otherwise ID will change!
        return $this->getURL().'/history/'.$this->groupHistoryModel->getCreatedAtTimeStamp();
    }

    public function getURL()
    {
        global $CONFIG;
        return $CONFIG->isSingleSiteMode ?
            'http://'.$CONFIG->webSiteDomain.'/group/'.$this->groupHistoryModel->getSlug() :
            'http://'.$this->siteModel->getSlug().".".$CONFIG->webSiteDomain.'/group/'.$this->groupHistoryModel->getSlug() ;
    }

    public function getTitle()
    {
        return $this->groupHistoryModel->getTitle();
    }

    public function getSummary()
    {
        $txt = '';

        if ($this->groupHistoryModel->getIsNew()) {
            $txt .= 'New! '."\n";
        }
        if ($this->groupHistoryModel->isAnyChangeFlagsUnknown()) {
            $txt .= $this->groupHistoryModel->getDescription();
        } else {
            if ($this->groupHistoryModel->getTitleChanged()) {
                $txt .= 'Title Changed. '."\n";
            }
            if ($this->groupHistoryModel->getDescriptionChanged()) {
                $txt .= 'Description Changed. '."\n";
            }
            if ($this->groupHistoryModel->getUrlChanged()) {
                $txt .= 'URL Changed. '."\n";
            }
            if ($this->groupHistoryModel->getTwitterUsernameChanged()) {
                $txt .= 'Twitter Changed. '."\n";
            }
            if ($this->groupHistoryModel->getIsDeletedChanged()) {
                $txt .= 'Deleted Changed: '.($this->groupHistoryModel->getIsDeleted() ? "Deleted":"Restored")."\n\n";
            }
        }
        return $txt;
    }
}
