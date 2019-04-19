<?php

namespace newsfeedmodels;

use models\SiteModel;
use models\TagHistoryModel;

/**
     *
     * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
     * @link https://gitlab.com/opentechcalendar You will find it's source here!
     * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
     * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
     */

class TagHistoryNewsFeedModel implements \InterfaceNewsFeedModel
{


    /** @var SiteModel */
    protected $siteModel;


    /** @var TagHistoryModel */
    protected $tagHistoryModel;

    public function __construct($tagHistoryModel, SiteModel $siteModel)
    {
        $this->tagHistoryModel = $tagHistoryModel;
        $this->siteModel = $siteModel;
    }


    /** @return \DateTime */
    public function getCreatedAt()
    {
        return $this->tagHistoryModel->getCreatedAt();
    }

    public function getID()
    {
        // For ID, must make sure we use Slug, not SlugForURL otherwise ID will change!
        return $this->getURL().'/history/'.$this->tagHistoryModel->getCreatedAtTimeStamp();
    }

    public function getURL()
    {
        global $CONFIG;
        return $CONFIG->isSingleSiteMode ?
            'http://'.$CONFIG->webSiteDomain.'/tag/'.$this->tagHistoryModel->getSlug() :
            'http://'.$this->siteModel->getSlug().".".$CONFIG->webSiteDomain.'/tag/'.$this->tagHistoryModel->getSlug() ;
    }

    public function getTitle()
    {
        return $this->tagHistoryModel->getTitle();
    }

    public function getSummary()
    {
        $txt = '';

        if ($this->tagHistoryModel->getIsNew()) {
            $txt .= 'New! '."\n";
        }
        if ($this->tagHistoryModel->isAnyChangeFlagsUnknown()) {
            $txt .= $this->tagHistoryModel->getDescription();
        } else {
            if ($this->tagHistoryModel->getTitleChanged()) {
                $txt .= 'Title Changed. '."\n";
            }
            if ($this->tagHistoryModel->getDescriptionChanged()) {
                $txt .= 'Description Changed. '."\n";
            }
            if ($this->tagHistoryModel->getIsDeletedChanged()) {
                $txt .= 'Deleted Changed: '.($this->tagHistoryModel->getIsDeleted() ? "Deleted":"Restored")."\n\n";
            }
        }
        return $txt;
    }
}
