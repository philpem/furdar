<?php

namespace newsfeedmodels;

use models\ImportHistoryModel;
use models\SiteModel;

/**
     *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
     */


class ImportHistoryNewsFeedModel implements \InterfaceNewsFeedModel
{


    /** @var SiteModel */
    protected $siteModel;



    /** @var ImportHistoryModel */
    protected $importHistoryModel;

    public function __construct($importHistoryModel, SiteModel $siteModel)
    {
        $this->importHistoryModel = $importHistoryModel;
        $this->siteModel = $siteModel;
    }


    /** @return \DateTime */
    public function getCreatedAt()
    {
        return $this->importHistoryModel->getCreatedAt();
    }

    public function getID()
    {
        // For ID, must make sure we use Slug, not SlugForURL otherwise ID will change!
        return $this->getURL().'/history/'.$this->importHistoryModel->getCreatedAtTimeStamp();
    }

    public function getURL()
    {
        global $CONFIG;
        return $CONFIG->isSingleSiteMode ?
            'http://'.$CONFIG->webSiteDomain.'/importurl/'.$this->importHistoryModel->getSlug() :
            'http://'.$this->siteModel->getSlug().".".$CONFIG->webSiteDomain.'/importurl/'.$this->importHistoryModel->getSlug() ;
    }

    public function getTitle()
    {
        return $this->importHistoryModel->getTitle();
    }

    public function getSummary()
    {
        $txt = '';

        if ($this->importHistoryModel->getIsNew()) {
            $txt .= 'New! '."\n";
        }
        if ($this->importHistoryModel->isAnyChangeFlagsUnknown()) {
            $txt .= $this->importHistoryModel->getTitle();
        } else {
            if ($this->importHistoryModel->getTitleChanged()) {
                $txt .= 'Title Changed. '."\n";
            }
            if ($this->importHistoryModel->getCountryIdChanged()) {
                $txt .= 'Country Changed. '."\n";
            }
            if ($this->importHistoryModel->getAreaIdChanged()) {
                $txt .= 'Area Changed. '."\n";
            }
            if ($this->importHistoryModel->getIsEnabledChanged()) {
                $txt .= 'Enabled Changed: '.($this->importHistoryModel->getIsEnabled() ? "Enabled":"Disabled")."\n\n";
            }
            if ($this->importHistoryModel->getExpiredAtChanged()) {
                $txt .= 'Expired Changed: '.($this->importHistoryModel->getExpiredAt() ? "Expired":"Not Expired")."\n\n";
            }
        }
        return $txt;
    }
}
