<?php

namespace newsfeedmodels;

use models\SiteModel;
use models\VenueHistoryModel;

/**
     *
     * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
     * @link https://gitlab.com/opentechcalendar You will find it's source here!
     * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
     * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
     */


class VenueHistoryNewsFeedModel implements \InterfaceNewsFeedModel
{


    /** @var SiteModel */
    protected $siteModel;

    /** @var VenueHistoryModel */
    protected $venueHistoryModel;

    public function __construct($venueHistoryModel, SiteModel $siteModel)
    {
        $this->venueHistoryModel = $venueHistoryModel;
        $this->siteModel = $siteModel;
    }


    /** @return \DateTime */
    public function getCreatedAt()
    {
        return $this->venueHistoryModel->getCreatedAt();
    }

    public function getID()
    {

        // For ID, must make sure we use Slug, not SlugForURL otherwise ID will change!
        return $this->getURL().'/history/'.$this->venueHistoryModel->getCreatedAtTimeStamp();
    }

    public function getURL()
    {
        global $CONFIG;
        return $CONFIG->isSingleSiteMode ?
            'http://'.$CONFIG->webSiteDomain.'/venue/'.$this->venueHistoryModel->getSlug() :
            'http://'.$this->siteModel->getSlug().".".$CONFIG->webSiteDomain.'/venue/'.$this->venueHistoryModel->getSlug() ;
    }

    public function getTitle()
    {
        return $this->venueHistoryModel->getTitle();
    }

    public function getSummary()
    {
        $txt = '';

        if ($this->venueHistoryModel->getIsNew()) {
            $txt .= 'New! '."\n";
        }
        if ($this->venueHistoryModel->isAnyChangeFlagsUnknown()) {
            $txt .= $this->venueHistoryModel->getDescription();
        } else {
            if ($this->venueHistoryModel->getTitleChanged()) {
                $txt .= 'Title Changed. '."\n";
            }
            if ($this->venueHistoryModel->getDescriptionChanged()) {
                $txt .= 'Description Changed. '."\n";
            }
            if ($this->venueHistoryModel->getAddressChanged()) {
                $txt .= 'Address Changed. '."\n";
            }
            if ($this->venueHistoryModel->getAddressCodeChanged()) {
                $txt .= 'Address Code Changed. '."\n";
            }
            if ($this->venueHistoryModel->getLatChanged() || $this->venueHistoryModel->getLngChanged()) {
                $txt .= 'Position on Map Changed. '."\n";
            }
            if ($this->venueHistoryModel->getAreaIdChanged()) {
                $txt .= 'Area Changed. '."\n";
            }
            if ($this->venueHistoryModel->getCountryIdChanged()) {
                $txt .= 'Country Changed. '."\n";
            }
            if ($this->venueHistoryModel->getIsDeletedChanged()) {
                $txt .= 'Deleted Changed: '.($this->venueHistoryModel->getIsDeleted() ? "Deleted":"Restored")."\n\n";
            }
        }
        return $txt;
    }
}
