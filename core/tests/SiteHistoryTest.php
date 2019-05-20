<?php

use models\SiteHistoryModel;
use models\UserAccountModel;
use models\SiteModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\SiteHistoryRepository;
use repositories\CountryRepository;
use \repositories\builders\HistoryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SiteHistoryTest extends \BaseAppTest
{

    /**
    function testIntegration1() {
         TODO


    }  **/
    
    public function testSetChangedFlagsFromNothing1()
    {
        $siteHistory = new SiteHistoryModel();
        $siteHistory->setFromDataBaseRow(array(
            'site_id'=>1,
            'title'=>'New Site',
            'slug'=>'new_site',
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
        ));
        
        $siteHistory->setChangedFlagsFromNothing();
        
        $this->assertEquals(true, $siteHistory->getTitleChanged());
        $this->assertEquals(true, $siteHistory->getSlugChanged());
        $this->assertEquals(false, $siteHistory->getDescriptionTextChanged());
        $this->assertEquals(false, $siteHistory->getFooterTextChanged());
        $this->assertEquals(true, $siteHistory->getIsWebRobotsAllowedChanged());
        $this->assertEquals(true, $siteHistory->getIsClosedBySysAdminChanged());
        $this->assertEquals(false, $siteHistory->getClosedBySyAdminReasonChanged());
        $this->assertEquals(true, $siteHistory->getIsListedInIndexChanged());
        $this->assertEquals(true, $siteHistory->getPromptEmailsDaysInAdvanceChanged());
        $this->assertEquals(true, $siteHistory->getIsNew());
    }
    
    
    public function testSetChangedFlagsFromLast1()
    {
        $lastHistory = new SiteHistoryModel();
        $lastHistory->setFromDataBaseRow(array(
            'site_id'=>1,
            'title'=>'New Site',
            'slug'=>'new_site',
            'footer_text'=>'',
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
        ));
        
        $siteHistory = new SiteHistoryModel();
        $siteHistory->setFromDataBaseRow(array(
            'site_id'=>1,
            'title'=>'New Site',
            'slug'=>'new_site',
            'footer_text'=>'Footer',
            'user_account_id'=>1,
            'created_at'=>'2014-02-01 10:00:00',
        ));
        
        $siteHistory->setChangedFlagsFromLast($lastHistory);
        

        $this->assertEquals(false, $siteHistory->getTitleChanged());
        $this->assertEquals(false, $siteHistory->getSlugChanged());
        $this->assertEquals(false, $siteHistory->getDescriptionTextChanged());
        $this->assertEquals(true, $siteHistory->getFooterTextChanged());
        $this->assertEquals(false, $siteHistory->getIsWebRobotsAllowedChanged());
        $this->assertEquals(false, $siteHistory->getIsClosedBySysAdminChanged());
        $this->assertEquals(false, $siteHistory->getClosedBySyAdminReasonChanged());
        $this->assertEquals(false, $siteHistory->getIsListedInIndexChanged());
        $this->assertEquals(false, $siteHistory->getPromptEmailsDaysInAdvanceChanged());
        $this->assertEquals(false, $siteHistory->getIsNew());
    }
}
