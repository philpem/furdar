<?php

namespace usernotifications\notifycontent;

use BaseUserWatchesNotifyContent;
use models\SiteModel;
use models\VenueModel;
use models\UserAccountModel;
use repositories\UserWatchesSiteRepository;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesSiteNotifyContent extends BaseUserWatchesNotifyContent
{

    /** @var  SiteModel */
    protected $site;

    /**
     * @param \models\SiteModel $site
     */
    public function setSite($site)
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

    public function markNotificationSent(\DateTime $checkTime)
    {
        global $app;
        $userWatchesSiteRepository = new UserWatchesSiteRepository($app);
        $userWatchesSite = $userWatchesSiteRepository->loadByUserAndSite($this->userAccount, $this->site);
        $userWatchesSiteRepository->markNotifyEmailSent($userWatchesSite, $checkTime);
    }
}
