<?php
    

namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteQuotaModel
{
    protected $id;
    protected $title;
    protected $code;
    protected $max_new_events_per_month;
    protected $max_new_groups_per_month;
    protected $max_new_venues_per_month;
    protected $max_countries;
    protected $max_media_mb;
    
    
    public function setFromDataBaseRow($data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->code = $data['code'];
        $this->max_new_events_per_month = $data['max_new_events_per_month'];
        $this->max_new_groups_per_month = $data['max_new_groups_per_month'];
        $this->max_new_venues_per_month = $data['max_new_venues_per_month'];
        $this->max_countries = $data['max_countries'];
        $this->max_media_mb = $data['max_media_mb'];
    }
        
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getMaxNewEventsPerMonth()
    {
        return $this->max_new_events_per_month;
    }

    public function setMaxNewEventsPerMonth($max_new_events_per_month)
    {
        $this->max_new_events_per_month = $max_new_events_per_month;
        return $this;
    }

    public function getMaxNewGroupsPerMonth()
    {
        return $this->max_new_groups_per_month;
    }

    public function setMaxNewGroupsPerMonth($max_new_groups_per_month)
    {
        $this->max_new_groups_per_month = $max_new_groups_per_month;
        return $this;
    }

    public function getMaxNewVenuesPerMonth()
    {
        return $this->max_new_venues_per_month;
    }

    public function setMaxNewVenuesPerMonth($max_new_venues_per_month)
    {
        $this->max_new_venues_per_month = $max_new_venues_per_month;
        return $this;
    }

    public function getMaxCountries()
    {
        return $this->max_countries;
    }

    public function setMaxCountries($max_countries)
    {
        $this->max_countries = $max_countries;
        return $this;
    }

    public function getMaxMediaMB()
    {
        return $this->max_media_mb;
    }

    public function setMaxMediaMB($max_media_mb)
    {
        $this->max_media_mb = $max_media_mb;
        return $this;
    }
}
