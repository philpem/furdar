<?php

use models\AreaModel;
use models\GroupModel;
use models\UserAccountModel;
use models\VenueModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class ExtensionOpenTechCalendar extends BaseExtension
{
    public function getId()
    {
        return 'uk.co.opentechcalendar';
    }

    public function getTitle()
    {
        return 'Open Tech Calendar';
    }


    public function getWEBAPI1URLsToRewrite()
    {
        $out = array();

        $out['/index.php/location/virtual/ical'] = array('url'=>'/api1/venue/virtual/ical');
        $out['/index.php/location/virtual/json'] = array('url'=>'/api1/venue/virtual/json');
        $out['/index.php/location/virtual/jsonp'] = array('url'=>'/api1/venue/virtual/jsonp');
        $out['/index.php/location/virtual/atom'] = array('url'=>'/api1/venue/virtual/atomCreate');

        $llrb = new \repositories\builders\LegacyLocationRepositoryBuilder();
        foreach ($llrb->fetchAll() as $legacyLocation) {
            $out['/index.php/location/'.$legacyLocation->getId().'/atom'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/atomCreate');
            $out['/index.php/location/'.$legacyLocation->getId().'/atomCreate'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/atomCreate');
            $out['/index.php/location/'.$legacyLocation->getId().'/atomBefore'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/atomBefore');
            $out['/index.php/location/'.$legacyLocation->getId().'/ical'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/ical');
            $out['/index.php/location/'.$legacyLocation->getId().'/json'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/json');
            $out['/index.php/location/'.$legacyLocation->getId().'/jsonp'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/jsonp');

            $out['/api1/legacylocation/'.$legacyLocation->getId().'/atomCreate'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/atomCreate');
            $out['/api1/legacylocation/'.$legacyLocation->getId().'/atomBefore'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/atomBefore');
            $out['/api1/legacylocation/'.$legacyLocation->getId().'/ical'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/ical');
            $out['/api1/legacylocation/'.$legacyLocation->getId().'/json'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/json');
            $out['/api1/legacylocation/'.$legacyLocation->getId().'/jsonp'] =  array('url'=>'/api1/area/'.$legacyLocation->getAreaId().'/jsonp');
        }

        $lrrb = new \repositories\builders\LegacyRegionRepositoryBuilder();
        foreach ($lrrb->fetchAll() as $legacyRegion) {
            $out['/index.php/country/GB/region/'.$legacyRegion->getId().'/ical'] =  array('url'=>'/api1/area/'.$legacyRegion->getAreaId().'/ical');
            $out['/index.php/country/GB/region/'.$legacyRegion->getId().'/json'] =  array('url'=>'/api1/area/'.$legacyRegion->getAreaId().'/json');
            $out['/index.php/country/GB/region/'.$legacyRegion->getId().'/jsonp'] =  array('url'=>'/api1/area/'.$legacyRegion->getAreaId().'/jsonp');
        }

        return $out;
    }

    public function showEmailPreferencesPrompt(UserAccountModel $userAccountModel = null)
    {
        if (!$userAccountModel) {
            return false;
        }

        $repo = new \repositories\UserNotificationPreferenceRepository($this->app);
        return !$repo->hasUserExpressedAnyPreferences($userAccountModel);
    }
}
