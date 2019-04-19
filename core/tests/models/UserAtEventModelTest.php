<?php

namespace tests\models;

use models\EventModel;
use models\EventRecurSetModel;
use models\SiteModel;
use models\UserAccountModel;
use models\UserAtEventModel;
use repositories\EventRecurSetRepository;
use repositories\EventRepository;
use repositories\SiteRepository;
use repositories\UserAccountRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserAtEventModelTest extends \BaseAppTest
{
    public function testUnknownThenYes()
    {
        $userAtModel = new UserAtEventModel();

        // CHECK

        $this->assertEquals(true, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());

        // CHANGE

        $userAtModel->setIsPlanAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanAttending());
    }

    public function testUnknownThenMaybe()
    {
        $userAtModel = new UserAtEventModel();

        // CHECK

        $this->assertEquals(true, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());

        // CHANGE

        $userAtModel->setIsPlanMaybeAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());
    }

    public function testUnknownThenNo()
    {
        $userAtModel = new UserAtEventModel();

        // CHECK

        $this->assertEquals(true, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());

        // CHANGE

        $userAtModel->setIsPlanNotAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());
    }

    public function testYesThenUnknown()
    {
        $userAtModel = new UserAtEventModel();
        $userAtModel->setIsPlanAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanAttending());

        // CHANGE

        $userAtModel->setIsPlanUnknownAttending(true);

        // CHECK

        $this->assertEquals(true, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());
    }

    public function testYesThenMaybe()
    {
        $userAtModel = new UserAtEventModel();
        $userAtModel->setIsPlanAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanAttending());

        // CHANGE

        $userAtModel->setIsPlanMaybeAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());
    }

    public function testYesThenNo()
    {
        $userAtModel = new UserAtEventModel();
        $userAtModel->setIsPlanAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanAttending());

        // CHANGE

        $userAtModel->setIsPlanNotAttending(true);

        // CHECK

        $this->assertEquals(false, $userAtModel->getIsPlanUnknownAttending());
        $this->assertEquals(true, $userAtModel->getIsPlanNotAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanMaybeAttending());
        $this->assertEquals(false, $userAtModel->getIsPlanAttending());
    }
}
