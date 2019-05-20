<?php

namespace tasks;

use repositories\builders\SiteRepositoryBuilder;
use repositories\builders\CountryRepositoryBuilder;
use repositories\EventCustomFieldDefinitionRepository;
use repositories\SiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UpdateSiteCacheTask extends \BaseTask
{
    public function getExtensionId()
    {
        return 'org.openacalendar';
    }

    public function getTaskId()
    {
        return 'UpdateSiteCache';
    }

    public function getShouldRunAutomaticallyNow()
    {
        return $this->app['config']->taskUpdateSiteCacheAutomaticUpdateInterval > 0 &&
        $this->getLastRunEndedAgoInSeconds() > $this->app['config']->taskUpdateSiteCacheAutomaticUpdateInterval;
    }

    protected function run()
    {
        $siteRepository = new SiteRepository($this->app);

        $eventCustomFieldsRepo = new EventCustomFieldDefinitionRepository($this->app);

        $siteRepositoryBuilder = new SiteRepositoryBuilder($this->app);
        $count = 0;
        foreach ($siteRepositoryBuilder->fetchAll() as $site) {
            $crb = new CountryRepositoryBuilder($this->app);
            $crb->setSiteIn($site);
            $countries = $crb->fetchAll();

            $timezones = array();
            foreach ($countries as $country) {
                foreach (explode(",", $country->getTimezones()) as $timeZone) {
                    $timezones[] = $timeZone;
                }
            }

            $site->setCachedTimezonesAsList($timezones);
            $site->setCachedIsMultipleCountries(count($countries) > 1);

            $siteRepository->editCached($site);

            $eventCustomFieldsRepo->updateSiteCache($site);

            ++$count;
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
