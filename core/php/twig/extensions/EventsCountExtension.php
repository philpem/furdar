<?php

namespace twig\extensions;

use repositories\builders\EventRepositoryBuilder;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventsCountExtension extends \Twig_Extension
{
    protected $app;
    protected $container;

    public function __construct(Application $app = null)
    {
        $this->app = $app;
    }

    public function getFunctions()
    {
        return array();
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('futureeventscount', array($this, 'futureEventsCount')),
            new \Twig_SimpleFilter('pasteventscount', array($this, 'pastEventsCount')),
        );
    }

    public function futureEventsCount($data)
    {
        global $app;

        $erb = new EventRepositoryBuilder($app);
        $erb->setSite($this->app['currentSite']);
        $erb->setAfterNow();
        $erb->setIncludeCancelled(true);
        $erb->setIncludeDeleted(false);

        if ($data instanceof \models\AreaModel) {
            $erb->setArea($data);
        } elseif ($data instanceof \models\GroupModel) {
            $erb->setGroup($data);
        } elseif ($data instanceof \models\VenueModel) {
            $erb->setVenue($data);
        } elseif ($data instanceof \models\TagModel) {
            $erb->setTag($data);
        } elseif ($data instanceof \models\CountryModel) {
            $erb->setCountry($data);
        }

        return $erb->fetchCount();
    }

    public function pastEventsCount($data)
    {
        global $app;

        $erb = new EventRepositoryBuilder($app);
        $erb->setSite($this->app['currentSite']);
        $erb->setBeforeNow();
        $erb->setIncludeCancelled(true);
        $erb->setIncludeDeleted(false);

        if ($data instanceof \models\AreaModel) {
            $erb->setArea($data);
        } elseif ($data instanceof \models\GroupModel) {
            $erb->setGroup($data);
        } elseif ($data instanceof \models\VenueModel) {
            $erb->setVenue($data);
        } elseif ($data instanceof \models\TagModel) {
            $erb->setTag($data);
        } elseif ($data instanceof \models\CountryModel) {
            $erb->setCountry($data);
        }

        return $erb->fetchCount();
    }


    public function getName()
    {
        return 'jarofgreen_wikicalendar_futureeventsextension';
    }
}
