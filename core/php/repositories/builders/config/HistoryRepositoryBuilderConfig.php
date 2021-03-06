<?php


namespace repositories\builders\config;

use models\AreaModel;
use models\MediaModel;
use models\SiteModel;
use models\EventModel;
use models\GroupModel;
use models\VenueModel;
use models\TagModel;
use models\ImportModel;
use models\UserAccountModel;
use models\EventHistoryModel;
use models\GroupHistoryModel;
use models\VenueHistoryModel;
use models\AreaHistoryModel;
use models\TagHistoryModel;
use models\ImportHistoryModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class HistoryRepositoryBuilderConfig
{
    protected $includeTagHistory = true;
    protected $includeEventHistory = true;
    protected $includeGroupHistory = true;
    protected $includeVenueHistory = true;
    protected $includeAreaHistory = true;
    protected $includeMediaHistory = true;
    protected $includeImportURLHistory = true;


    public function getIncludeEventHistory()
    {
        return $this->includeEventHistory;
    }

    public function setIncludeEventHistory($includeEventHistory)
    {
        $this->includeEventHistory = $includeEventHistory;
    }

    public function getIncludeGroupHistory()
    {
        return $this->includeGroupHistory;
    }

    public function setIncludeGroupHistory($includeGroupHistory)
    {
        $this->includeGroupHistory = $includeGroupHistory;
    }

    public function getIncludeTagHistory()
    {
        return $this->includeTagHistory;
    }

    public function setIncludeTagHistory($includeTagHistory)
    {
        $this->includeTagHistory = $includeTagHistory;
    }

    public function getIncludeVenueHistory()
    {
        return $this->includeVenueHistory;
    }

    public function setIncludeVenueHistory($includeVenueHistory)
    {
        $this->includeVenueHistory = $includeVenueHistory;
    }

    public function getIncludeAreaHistory()
    {
        return $this->includeAreaHistory;
    }

    public function setIncludeAreaHistory($includeAreaHistory)
    {
        $this->includeAreaHistory = $includeAreaHistory;
    }

    public function getIncludeImportURLHistory()
    {
        return $this->includeImportURLHistory;
    }

    public function setIncludeImportURLHistory($includeImportURLHistory)
    {
        $this->includeImportURLHistory = $includeImportURLHistory;
    }

    /**
     * @return boolean
     */
    public function getIncludeMediaHistory()
    {
        return $this->includeMediaHistory;
    }

    /**
     * @param boolean $includeMediaHistory
     */
    public function setIncludeMediaHistory($includeMediaHistory)
    {
        $this->includeMediaHistory = $includeMediaHistory;
    }




    protected $since;

    public function setSince($since)
    {
        $this->since = $since;
    }

    /** @var SiteModel **/
    protected $site;

    public function setSite(SiteModel $site)
    {
        $this->site = $site;
        $this->includeEventHistory = true;
        $this->includeGroupHistory = true;
        $this->includeVenueHistory = true;
        $this->includeAreaHistory = true;
        $this->includeTagHistory = true;
        $this->includeMediaHistory = true;
        $this->includeImportURLHistory = true;
    }


    /** @var GroupModel **/
    protected $group;

    public function setGroup(GroupModel $group)
    {
        $this->group = $group;
        $this->includeEventHistory = true;
        $this->includeGroupHistory = true;
        $this->includeVenueHistory = false;
        $this->includeAreaHistory = false;
        $this->includeTagHistory = false;
        $this->includeMediaHistory = false;
        $this->includeImportURLHistory = true;
    }

    /** @var EventModel **/
    protected $event;

    public function setEvent(EventModel $event)
    {
        $this->event = $event;
        $this->includeEventHistory = true;
        $this->includeGroupHistory = true;
        $this->includeVenueHistory = true;
        $this->includeAreaHistory = false;
        $this->includeTagHistory = false;
        $this->includeMediaHistory = false;
        $this->includeImportURLHistory = false;
    }

    /** @var VenueModel **/
    protected $venue;

    public function setVenue(VenueModel $venue)
    {
        $this->venue = $venue;
        $this->includeEventHistory = true;
        $this->includeGroupHistory = false;
        $this->includeVenueHistory = true;
        $this->includeAreaHistory = false;
        $this->includeTagHistory = false;
        $this->includeMediaHistory = false;
        $this->includeImportURLHistory = false;
    }

    /** @var TagModel **/
    protected $tag;

    public function setTag(TagModel $tag)
    {
        $this->tag = $tag;
        $this->includeEventHistory = false;
        $this->includeGroupHistory = false;
        $this->includeVenueHistory = false;
        $this->includeAreaHistory = false;
        $this->includeTagHistory = true;
        $this->includeImportURLHistory = false;
    }

    /** @var AreaModel **/
    protected $area;

    public function setArea(AreaModel $area)
    {
        $this->area = $area;
        $this->includeEventHistory = true;
        $this->includeGroupHistory = false;
        $this->includeVenueHistory = false;
        $this->includeAreaHistory = true;
        $this->includeTagHistory = false;
        $this->includeMediaHistory = false;
        $this->includeImportURLHistory = false;
    }

    /** @var MediaModel **/
    protected $media;

    public function setMedia(MediaModel $media)
    {
        $this->media = $media;
        $this->includeEventHistory = false;
        $this->includeGroupHistory = false;
        $this->includeVenueHistory = false;
        $this->includeAreaHistory = false;
        $this->includeTagHistory = false;
        $this->includeMediaHistory = true;
        $this->includeImportURLHistory = false;
    }


    protected $venueVirtualOnly = false;


    public function setVenueVirtualOnly($value)
    {
        $this->venueVirtualOnly = $value;
        if ($value) {
            $this->includeEventHistory = true;
            $this->includeGroupHistory = false;
            $this->includeVenueHistory = false;
            $this->includeAreaHistory = false;
            $this->includeTagHistory = false;
            $this->includeMediaHistory = false;
            $this->includeImportURLHistory = false;
        }
    }

    /** @var UserModel **/
    protected $notUser;

    public function setNotUser(UserAccountModel $notUser)
    {
        $this->notUser = $notUser;
    }




    protected $limit = 50;



    /**
     * @param int $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return \models\EventModel
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return \models\GroupModel
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @return MediaModel
     */
    public function getMedia()
    {
        return $this->media;
    }



    /**
     * @return \repositories\builders\UserModel
     */
    public function getNotUser()
    {
        return $this->notUser;
    }

    /**
     * @return mixed
     */
    public function getSince()
    {
        return $this->since;
    }

    /**
     * @return \models\SiteModel
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return \models\TagModel
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @return \models\VenueModel
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * @return boolean
     */
    public function getVenueVirtualOnly()
    {
        return $this->venueVirtualOnly;
    }

    /**
     * @return \models\AreaModel
     */
    public function getArea()
    {
        return $this->area;
    }
}
