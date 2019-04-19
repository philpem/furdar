<?php


namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class LegacyRegionModel
{
    protected $id;
    protected $title;
    protected $country_id;
    protected $area_id;


    public function setFromDataBaseRow($data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->country_id = $data['country_id'];
        $this->area_id = $data['area_id'];
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getCountry_id()
    {
        return $this->country_id;
    }

    public function setCountry_id($country_id)
    {
        $this->country_id = $country_id;
    }
    
    public function getAreaId()
    {
        return $this->area_id;
    }
}
