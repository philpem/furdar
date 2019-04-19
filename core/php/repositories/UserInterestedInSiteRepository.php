<?php

namespace repositories;

use models\SiteModel;
use models\UserAccountModel;
use models\UserInterestedInSiteModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserInterestedInSiteRepository
{


    /** @var Application */
    private $app;


    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function loadByUserAndSite(UserAccountModel $user, SiteModel $site)
    {
        return $this->loadByUserAndSiteId($user, $site->getId());
    }

    public function loadByUserAndSiteId(UserAccountModel $user, int $siteID)
    {
        $stat = $this->app['db']->prepare("SELECT user_interested_in_site_information.* FROM user_interested_in_site_information WHERE user_account_id =:user_account_id AND site_id=:site_id");
        $stat->execute(array('user_account_id' => $user->getId(), 'site_id' => $siteID));
        if ($stat->rowCount() > 0) {
            $uiis = new UserInterestedInSiteModel();
            $uiis->setFromDataBaseRow($stat->fetch());
            return $uiis;
        }
    }

    public function markUserInterestedInSite(UserAccountModel $user, SiteModel $site)
    {
        $uiis = $this->loadByUserAndSite($user, $site);
        if ($uiis && $uiis->isInterested()) {
            // all done!
        } elseif ($uiis && !$uiis->isInterested()) {
            $stat = $this->app['db']->prepare("UPDATE user_interested_in_site_information SET is_interested='1' WHERE user_account_id =:user_account_id AND site_id=:site_id");
            $stat->execute(array('user_account_id' => $user->getId(), 'site_id' => $site->getId()));
        } else {
            $stat = $this->app['db']->prepare("INSERT INTO user_interested_in_site_information (user_account_id,site_id,is_interested,created_at) " .
                "VALUES (:user_account_id,:site_id,:is_interested,:created_at)");
            $stat->execute(array(
                'user_account_id' => $user->getId(),
                'site_id' => $site->getId(),
                'is_interested' => '1',
                'created_at' => $this->app['timesource']->getFormattedForDataBase(),
            ));
        }
    }
}
