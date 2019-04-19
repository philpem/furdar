<?php

namespace repositories\builders\filterparams;

use models\SiteModel;
use models\EventModel;
use models\GroupModel;
use repositories\builders\VenueRepositoryBuilder;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class VenueFilterParams
{
    public function __construct(Application $app)
    {
        $this->venueRepositoryBuilder = new VenueRepositoryBuilder($app);
        $this->venueRepositoryBuilder->setLimit(100);
    }

    
    protected $venueRepositoryBuilder;
    
    public function getVenueRepositoryBuilder()
    {
        return $this->venueRepositoryBuilder;
    }

    public function isDefaultFilters()
    {
        return !$this->freeTextSearch && !$this->withFutureEventsOnly;
    }
        
    // ############################### params
    
    protected $freeTextSearch = null;
    protected $withFutureEventsOnly = false;
    
    public function set($data)
    {
        if (isset($data['venueListFilterDataSubmitted'])) {
        
            // Free Text Search
            if (isset($data['freeTextSearch']) && trim($data['freeTextSearch'])) {
                $this->freeTextSearch = $data['freeTextSearch'];
            }

            // Future Events Only
            if (isset($data['withFutureEventsOnly']) && $data['withFutureEventsOnly'] == '1') {
                $this->withFutureEventsOnly = true;
            }
        }
        
        // apply to search
        if ($this->freeTextSearch) {
            $this->venueRepositoryBuilder->setFreeTextsearch($this->freeTextSearch);
        }


        $this->venueRepositoryBuilder->setIncludeFutureEventsOnly($this->withFutureEventsOnly);
    }
    
    public function getFreeTextSearch()
    {
        return $this->freeTextSearch;
    }


    /**
     * @return boolean
     */
    public function isWithFutureEventsOnly()
    {
        return $this->withFutureEventsOnly;
    }


    public function getHumanTextRepresentation()
    {
        $out = array();

        // WITH FUTURE EVENTS ONLY
        if ($this->withFutureEventsOnly) {
            $out[] = 'with future events only';
        }

        // FREE TEXT
        if ($this->freeTextSearch) {
            $out[]  = 'free text search: '.$this->freeTextSearch;
        }

        return implode(", ", $out);
    }
}
