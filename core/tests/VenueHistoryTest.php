<?php

use models\VenueHistoryModel;
use models\UserAccountModel;
use models\SiteModel;
use models\VenueModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\VenueRepository;
use repositories\VenueHistoryRepository;
use repositories\CountryRepository;
use \repositories\builders\HistoryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueHistoryTest extends \BaseAppTest
{
    public function testSetChangedFlagsFromNothing1()
    {
        $venueHistory = new VenueHistoryModel();
        $venueHistory->setFromDataBaseRow(array(
            'venue_id'=>1,
            'title'=>'New Venue',
            'description'=>'',
            'lat'=>3.5,
            'lng'=>3.5,
            'country_id'=>88,
            'area_id'=>4,
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
            'is_deleted'=>0,
            'is_duplicate_of_id'=>null,
            'address'=>'',
            'address_code'=>'',
            'title_changed'=>0,
            'description_changed'=>0,
            'country_id_changed'=>0,
            'area_id_changed'=>0,
            'lng_changed'=>0,
            'lat_changed'=>0,
            'area_id_changed'=>0,
            'is_deleted_changed'=>0,
            'address_changed'=>0,
            'address_code_changed'=>0,
        ));
        
        $venueHistory->setChangedFlagsFromNothing();
        
        $this->assertEquals(true, $venueHistory->getTitleChanged());
        $this->assertEquals(false, $venueHistory->getDescriptionChanged());
        $this->assertEquals(true, $venueHistory->getCountryIdChanged());
        $this->assertEquals(true, $venueHistory->getAreaIdChanged());
        $this->assertEquals(false, $venueHistory->getIsDeletedChanged());
        $this->assertEquals(true, $venueHistory->getLatChanged());
        $this->assertEquals(true, $venueHistory->getLngChanged());
        $this->assertEquals(true, $venueHistory->getIsNew());
    }
    
    public function testSetChangedFlagsFromNothing2()
    {
        $venueHistory = new VenueHistoryModel();
        $venueHistory->setFromDataBaseRow(array(
            'venue_id'=>1,
            'title'=>'',
            'description'=>'CAT',
            'lat'=>null,
            'lng'=>null,
            'country_id'=>88,
            'area_id'=>4,
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
            'is_deleted'=>0,
            'is_duplicate_of_id'=>null,
            'address'=>'',
            'address_code'=>'',
            'title_changed'=>0,
            'description_changed'=>0,
            'country_id_changed'=>0,
            'area_id_changed'=>0,
            'lng_changed'=>0,
            'lat_changed'=>0,
            'area_id_changed'=>0,
            'is_deleted_changed'=>0,
            'address_changed'=>0,
            'address_code_changed'=>0,
        ));
        
        $venueHistory->setChangedFlagsFromNothing();
        
        $this->assertEquals(false, $venueHistory->getTitleChanged());
        $this->assertEquals(true, $venueHistory->getDescriptionChanged());
        $this->assertEquals(true, $venueHistory->getCountryIdChanged());
        $this->assertEquals(true, $venueHistory->getAreaIdChanged());
        $this->assertEquals(false, $venueHistory->getIsDeletedChanged());
        $this->assertEquals(false, $venueHistory->getLatChanged());
        $this->assertEquals(false, $venueHistory->getLngChanged());
        $this->assertEquals(true, $venueHistory->getIsNew());
    }
    
    
    public function testSetChangedFlagsFromLast1()
    {
        $lastHistory = new VenueHistoryModel();
        $lastHistory->setFromDataBaseRow(array(
            'venue_id'=>1,
            'title'=>'New Venue',
            'description'=>'',
            'lat'=>3.5,
            'lng'=>3.5,
            'country_id'=>88,
            'area_id'=>4,
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
            'is_deleted'=>0,
            'is_duplicate_of_id'=>null,
            'address'=>'',
            'address_code'=>'',
            'title_changed'=>0,
            'description_changed'=>0,
            'country_id_changed'=>0,
            'area_id_changed'=>0,
            'lng_changed'=>0,
            'lat_changed'=>0,
            'area_id_changed'=>0,
            'is_deleted_changed'=>0,
            'address_changed'=>0,
            'address_code_changed'=>0,
        ));
        
        $venueHistory = new VenueHistoryModel();
        $venueHistory->setFromDataBaseRow(array(
            'venue_id'=>1,
            'title'=>'New Venue',
            'description'=>'cat dog',
            'lat'=>4.5,
            'lng'=>3.5,
            'country_id'=>88,
            'area_id'=>4,
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
            'is_deleted'=>0,
            'is_duplicate_of_id'=>null,
            'address'=>'',
            'address_code'=>'',
            'title_changed'=>0,
            'description_changed'=>0,
            'country_id_changed'=>0,
            'area_id_changed'=>0,
            'lng_changed'=>0,
            'lat_changed'=>0,
            'area_id_changed'=>0,
            'is_deleted_changed'=>0,
            'address_changed'=>0,
            'address_code_changed'=>0,
        ));
        
        $venueHistory->setChangedFlagsFromLast($lastHistory);
        
        $this->assertEquals(false, $venueHistory->getTitleChanged());
        $this->assertEquals(true, $venueHistory->getDescriptionChanged());
        $this->assertEquals(false, $venueHistory->getCountryIdChanged());
        $this->assertEquals(false, $venueHistory->getIsDeletedChanged());
        $this->assertEquals(true, $venueHistory->getLatChanged());
        $this->assertEquals(false, $venueHistory->getLngChanged());
        $this->assertEquals(false, $venueHistory->getIsNew());
    }
}
