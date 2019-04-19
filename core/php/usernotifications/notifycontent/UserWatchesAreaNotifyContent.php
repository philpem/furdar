<?php

namespace usernotifications\notifycontent;

use BaseUserWatchesNotifyContent;
use models\SiteModel;
use models\VenueModel;
use models\UserAccountModel;
use repositories\UserWatchesAreaRepository;
use repositories\UserWatchesSiteRepository;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesAreaNotifyContent extends BaseUserWatchesNotifyContent
{

    /** @var  SiteModel */
    protected $site;

    /**
     * @param \models\SiteModel $site
     */
    public function setSite(\models\SiteModel $site)
    {
        $this->site = $site;
    }

    /**
     * @return \models\SiteModel
     */
    public function getSite()
    {
        return $this->site;
    }

    /** @var AreaModel */
    protected $area;

    /**
     * @param mixed $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * @return mixed
     */
    public function getArea()
    {
        return $this->area;
    }

    public function markNotificationSent(\DateTime $checkTime)
    {
        global $app;
        $userWatchesAreaRepository = new UserWatchesAreaRepository($app);
        $userWatchesArea = $userWatchesAreaRepository->loadByUserAndArea($this->userAccount, $this->area);
        $userWatchesAreaRepository->markNotifyEmailSent($userWatchesArea, $checkTime);
    }
}
