<?php
namespace api1exportbuilders;

use repositories\builders\EventRepositoryBuilder;
use models\SiteModel;
use models\EventModel;
use models\VenueModel;
use models\AreaModel;
use models\CountryModel;
use repositories\builders\MediaRepositoryBuilder;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseEventListBuilder extends BaseBuilder
{
    protected $eventRepositoryBuilder;
    
    protected $events = array();


    protected $includeEventMedias = false;

    /**
     * @param boolean $includeEventMedias
     */
    public function setIncludeEventMedias($includeEventMedias)
    {
        $this->includeEventMedias = $includeEventMedias;
    }

    /**
     * @return boolean
     */
    public function getIncludeEventMedias()
    {
        return $this->includeEventMedias;
    }



    public function __construct(Application $app, SiteModel $site = null, string $timeZone = null, string $title = null)
    {
        parent::__construct($app, $site, $timeZone, $title);
        $this->eventRepositoryBuilder = new EventRepositoryBuilder($app);
        $this->eventRepositoryBuilder->setLimit($this->app['config']->api1EventListLimit);
        $this->eventRepositoryBuilder->setIncludeCountryInformation(true);
        $this->eventRepositoryBuilder->setIncludeAreaInformation(true);
        $this->eventRepositoryBuilder->setIncludeVenueInformation(true);
        if ($site) {
            $this->eventRepositoryBuilder->setSite($site);
        }
    }

    abstract public function addEvent(
        EventModel $event,
        $groups = array(),
        VenueModel $venue = null,
        AreaModel $area = null,
        CountryModel $country = null,
        $eventMedias = array()
    );

    
    public function build()
    {
        foreach ($this->eventRepositoryBuilder->fetchAll() as $event) {
            $eventMedias = null;
            if ($this->includeEventMedias) {
                $mrb = new MediaRepositoryBuilder($this->app);
                $mrb->setEvent($event);
                $mrb->setIncludeDeleted(false);
                $eventMedias = $mrb->fetchAll();
            }
            $this->addEvent($event, null, $event->getVenue(), $event->getArea(), $event->getCountry(), $eventMedias);
        }
    }
        
    public function getEventRepositoryBuilder()
    {
        return $this->eventRepositoryBuilder;
    }
}
