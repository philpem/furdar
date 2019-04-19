<?php
namespace actions;

use models\AreaModel;
use models\CountryModel;
use models\SiteModel;
use repositories\builders\AreaRepositoryBuilder;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GetAreaForLatLng
{
    protected $app;

    /** @var  SiteModel */
    protected $site;

    public function __construct($app, SiteModel $site)
    {
        $this->app  = $app;
        $this->site = $site;
    }


    public function getArea(float $lat, float $lng, CountryModel $countryModel = null)
    {
        $areaRepo = new AreaRepositoryBuilder($this->app);
        $areaRepo->setSite($this->site);
        if ($countryModel) {
            $areaRepo->setCountry($countryModel);
        }
        $areaRepo->setIncludeDeleted(false);
        $areaRepo->setLatLng($lat, $lng);

        $areas = $areaRepo->fetchAll();

        if (count($areas) == 0) {
            return null;
        } elseif (count($areas) == 1) {
            return array_pop($areas);
        } else {
            $newAreas = array();

            foreach ($areas as $area) {
                if (!$this->isAreaEntirelyBiggerThanOtherArea($area, $areas)) {
                    $newAreas[] = $area;
                }
            }

            if (count($newAreas) == 1) {
                return array_pop($newAreas);
            }
        }
    }

    protected function isAreaEntirelyBiggerThanOtherArea(AreaModel $area, array $areas)
    {
        foreach ($areas as $otherArea) {
            if ($otherArea->getId() != $area->getId()
                && $otherArea->getMaxLat() < $area->getMaxLat()
                && $otherArea->getMaxLng() < $area->getMaxLng()
                && $otherArea->getMinLat() > $area->getMinLat()
                && $otherArea->getMinLng() > $area->getMinLng()
            ) {
                return true;
            }
        }
        return false;
    }
}
