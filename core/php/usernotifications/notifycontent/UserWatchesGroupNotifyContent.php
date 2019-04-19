<?php

namespace usernotifications\notifycontent;

use BaseUserWatchesNotifyContent;
use models\SiteModel;
use models\VenueModel;
use models\UserAccountModel;
use repositories\UserWatchesGroupRepository;
use repositories\UserWatchesSiteRepository;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesGroupNotifyContent extends BaseUserWatchesNotifyContent
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

    /** @var GroupModel */
    protected $group;

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    public function markNotificationSent(\DateTime $checkTime)
    {
        global $app;
        $userWatchesGroupRepository = new UserWatchesGroupRepository($app);
        $userWatchesGroup = $userWatchesGroupRepository->loadByUserAndGroup($this->userAccount, $this->group);
        $userWatchesGroupRepository->markNotifyEmailSent($userWatchesGroup, $checkTime);
    }
}
