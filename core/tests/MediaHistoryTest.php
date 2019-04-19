<?php

use models\MediaHistoryModel;
use models\UserAccountModel;
use models\SiteModel;
use models\MediaModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\MediaRepository;
use repositories\MediaHistoryRepository;
use \repositories\builders\HistoryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class MediaHistoryTest extends \BaseAppTest
{
    public function testSetChangedFlagsFromNothing1()
    {
        $mediaHistory = new MediaHistoryModel();
        $mediaHistory->setFromDataBaseRow(array(
            'media_id'=>1,
            'title'=>'New Media',
            'source_text'=>'Text',
            'source_url'=>'',
            'created_at'=>'2014-02-01 10:00:00',
            'title_changed'=>0,
            'source_url_changed'=>0,
            'source_text_changed'=>0,
            'user_account_id'=>1,
        ));
        
        $mediaHistory->setChangedFlagsFromNothing();
        
        $this->assertEquals(true, $mediaHistory->getTitleChanged());
        $this->assertEquals(true, $mediaHistory->getSourceTextChanged());
        $this->assertEquals(false, $mediaHistory->getSourceURLChanged());
        $this->assertEquals(true, $mediaHistory->getIsNew());
    }


    
    public function testSetChangedFlagsFromLast1()
    {
        $lastHistory = new MediaHistoryModel();
        $lastHistory->setFromDataBaseRow(array(
            'media_id'=>1,
            'title'=>'New Media',
            'source_text'=>'',
            'source_url'=>'',
            'created_at'=>'2014-01-01 10:00:00',
            'title_changed'=>0,
            'source_url_changed'=>0,
            'source_text_changed'=>0,
            'user_account_id'=>1,
        ));
        
        $mediaHistory = new MediaHistoryModel();
        $mediaHistory->setFromDataBaseRow(array(
            'media_id'=>1,
            'title'=>'New Media',
            'source_text'=>'Text',
            'source_url'=>'http://www.google.com',
            'created_at'=>'2014-02-01 10:00:00',
            'title_changed'=>0,
            'source_url_changed'=>0,
            'source_text_changed'=>0,
            'user_account_id'=>1,
        ));
        
        $mediaHistory->setChangedFlagsFromLast($lastHistory);
        
        $this->assertEquals(false, $mediaHistory->getTitleChanged());
        $this->assertEquals(true, $mediaHistory->getSourceTextChanged());
        $this->assertEquals(true, $mediaHistory->getSourceURLChanged());
        $this->assertEquals(false, $mediaHistory->getIsNew());
    }
}
