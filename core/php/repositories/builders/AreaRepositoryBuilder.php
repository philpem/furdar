<?php

namespace repositories\builders;

use models\SiteModel;
use models\UserAccountModel;
use models\VenueModel;
use models\CountryModel;
use models\AreaModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class AreaRepositoryBuilder extends BaseRepositoryBuilder
{
    

    /** @var SiteModel **/
    protected $site;
    
    public function setSite(SiteModel $site)
    {
        $this->site = $site;
    }

    /** @var CountryModel **/
    protected $country;
    
    public function setCountry(CountryModel $country)
    {
        $this->country = $country;
    }

    /** @var AreaModel **/
    protected $parentArea;
    
    public function setParentArea(AreaModel $area)
    {
        $this->parentArea = $area;
    }

    protected $noParentArea = false;
    
    public function setNoParentArea($noParentArea)
    {
        $this->noParentArea = $noParentArea;
    }

    
    protected $cacheNeedsBuildingOnly = false;
    
    public function setCacheNeedsBuildingOnly($cacheNeedsBuildingOnly)
    {
        $this->cacheNeedsBuildingOnly = $cacheNeedsBuildingOnly;
    }

        

    protected $freeTextSearch;

    public function setFreeTextSearch($freeTextSearch)
    {
        $this->freeTextSearch = $freeTextSearch;
    }



    protected $include_deleted = true;

    public function setIncludeDeleted($value)
    {
        $this->include_deleted = $value;
    }


    public $include_parent_levels = 0;

    /**
     * @param int $include_parent_levels
     */
    public function setIncludeParentLevels($include_parent_levels)
    {
        $this->include_parent_levels = $include_parent_levels;
    }


    /** @var UserAccountModel  */
    protected $editedByUser = null;

    /**
     * @param UserAccountModel $editedByUser
     */
    public function setEditedByUser(UserAccountModel $editedByUser)
    {
        $this->editedByUser = $editedByUser;
    }



    protected $titleSearch;

    /**
     * @param mixed $titleSearch
     */
    public function setTitleSearch($titleSearch)
    {
        $this->titleSearch = $titleSearch;
    }

    protected $lat;

    protected $lng;

    public function setLatLng($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    protected function build()
    {
        $this->select[] = 'area_information.*';

        if ($this->site) {
            $this->where[] =  " area_information.site_id = :site_id ";
            $this->params['site_id'] = $this->site->getId();
        }
        
        if ($this->country) {
            $this->where[] =  " area_information.country_id = :country_id ";
            $this->params['country_id'] = $this->country->getId();
        }
        
        if (!$this->include_deleted) {
            $this->where[] = " area_information.is_deleted = '0' ";
        }
        
        if ($this->noParentArea) {
            $this->where[] = ' area_information.parent_area_id IS null ';
        } elseif ($this->parentArea) {
            $this->where[] =  " area_information.parent_area_id = :parent_id ";
            $this->params['parent_id'] = $this->parentArea->getId();
        }
        
        if ($this->cacheNeedsBuildingOnly) {
            $this->where[] = " area_information.cache_area_has_parent_generated = '0'";
        }

        if ($this->freeTextSearch) {
            $this->where[] =  ' area_information.title ILIKE :free_text_search ';
            $this->params['free_text_search'] = "%".strtolower($this->freeTextSearch)."%";
        }

        if ($this->titleSearch) {
            $this->where[] =  'area_information.title ILIKE :title_search ';
            $this->params['title_search'] = "%".strtolower($this->titleSearch)."%";
        }

        if ($this->include_parent_levels > 0) {
            $this->joins[] = " LEFT JOIN area_information AS area_information_parent_1 ON area_information.parent_area_id = area_information_parent_1.id ";
            $this->select[] = " area_information_parent_1.title AS parent_1_title";
        }

        if ($this->editedByUser) {
            $this->where[] = " area_information.id IN (SELECT area_id FROM area_history WHERE user_account_id = :editedByUser) ";
            $this->params['editedByUser'] = $this->editedByUser->getId();
        }

        if ($this->lat && $this->lng) {
            $this->where[] = " area_information.min_lat <= :lat AND area_information.max_lat >= :lat AND area_information.min_lng <= :lng AND area_information.max_lng >= :lng ";
            $this->params['lat'] = $this->lat;
            $this->params['lng'] = $this->lng;
        }
    }
    
    protected function buildStat()
    {
        $sql = "SELECT " . implode(", ", $this->select) . " FROM area_information ".
                implode(" ", $this->joins).
                ($this->where ? " WHERE ".implode(" AND ", $this->where) : '').
                " ORDER BY area_information.title ASC ".($this->limit > 0 ? " LIMIT ". $this->limit : "");
    
        $this->stat = $this->app['db']->prepare($sql);
        $this->stat->execute($this->params);
    }
    
    
    public function fetchAll()
    {
        $this->buildStart();
        $this->build();
        $this->buildStat();
        
    
        $results = array();
        while ($data = $this->stat->fetch()) {
            $area = new AreaModel();
            $area->setFromDataBaseRow($data);
            $results[] = $area;
        }
        return $results;
    }
}
