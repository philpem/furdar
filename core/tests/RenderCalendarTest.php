<?php

use models\EventHistoryModel;
use models\UserAccountModel;
use models\SiteModel;
use models\EventModel;
use repositories\UserAccountRepository;
use repositories\SiteRepository;
use repositories\EventRepository;
use repositories\EventHistoryRepository;
use repositories\CountryRepository;
use \repositories\builders\HistoryRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class RenderCalendarTest extends \BaseAppTest
{
    public function dataForTestByDate()
    {
        return array(
                array(2014,4,28,  31,true,  2014,4,28,  2014,6,1,  2014,5),
                array(2014,4,9,  31,true,  2014,4,7,  2014,5,11,  2014,4),
                array(2014,12,2,  31,true,  2014,12,1,  2015,1,4,  2014,12),
                array(2014,12,20,  31,true,  2014,12,15,  2015,1,18,  2014,12),
                array(2014,12,27,  31,true,  2014,12,22,  2015,1,25,  2015,1),
            );
    }
    
    /**
     * @dataProvider dataForTestByDate
     */
    public function testByDate($inYear, $inMonth, $inDay, $days, $expand, $startYear, $startMonth, $startDate, $endYear, $endMonth, $endDate, $outYear, $outMonth)
    {
        $this->app['timesource']->mock($inYear, $inMonth, $inDay, 1, 2, 3);
        
        $cal = new RenderCalendar($this->app);
        
        $inDate = new \DateTime();
        $inDate->setDate($inYear, $inMonth, $inDay);
        $cal->byDate($inDate, $days, $expand);
        
        $start = $cal->getStart();
        $this->assertEquals($startYear, intval($start->format("Y")));
        $this->assertEquals($startMonth, intval($start->format("n")));
        $this->assertEquals($startDate, intval($start->format("j")));

        $end = $cal->getEnd();
        $this->assertEquals($endYear, intval($end->format("Y")));
        $this->assertEquals($endMonth, intval($end->format("n")));
        $this->assertEquals($endDate, intval($end->format("j")));

        $this->assertEquals($outYear, $cal->getYear());
        $this->assertEquals($outMonth, $cal->getMonth());
    }
}
