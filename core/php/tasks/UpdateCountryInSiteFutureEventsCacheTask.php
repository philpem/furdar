<?php

namespace tasks;

use models\SiteModel;
use repositories\builders\CountryRepositoryBuilder;
use repositories\builders\SiteRepositoryBuilder;
use repositories\CountryInSiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateCountryInSiteFutureEventsCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateCountryInSiteFutureEventsCache';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateCountryInSiteFutureEventsCacheAutomaticUpdateInterval > 0 &&
               $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateCountryInSiteFutureEventsCacheAutomaticUpdateInterval;
    }

    protected function run()
    {
        $cisRepo = new CountryInSiteRepository($this->app);

        $srb = new SiteRepositoryBuilder($this->app);
        $count = 0;
        /** @var SiteModel $site */
        foreach ($srb->fetchAll() as $site) {
            if (!$site->getIsClosedBySysAdmin()) {
                $crb = new CountryRepositoryBuilder($this->app);
                $crb->setSiteIn($site);
                foreach ($crb->fetchAll() as $country) {
                    $cisRepo->updateFutureEventsCache($country, $site);
                    ++$count;
                }
            }
        }

        return array('result'=>'ok','count'=>$count);
    }

    public function getResultDataAsString(\models\TaskLogModel $taskLogModel)
    {
        if ($taskLogModel->getIsResultDataHaveKey("result") && $taskLogModel->getResultDataValue("result") == "ok") {
            return "Ok";
        } else {
            return "Fail";
        }
    }
}
