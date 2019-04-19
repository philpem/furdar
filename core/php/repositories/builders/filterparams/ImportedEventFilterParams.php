<?php

namespace repositories\builders\filterparams;

use repositories\builders\ImportedEventRepositoryBuilder;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportedEventFilterParams
{
    public function __construct(Application $app, ImportedEventRepositoryBuilder $erb = null)
    {
        if ($erb) {
            $this->importedEventRepositoryBuilder = $erb;
        } else {
            $this->importedEventRepositoryBuilder = new ImportedEventRepositoryBuilder($app);
            $this->importedEventRepositoryBuilder->setLimit(100);
        }
    }

    
    protected $importedEventRepositoryBuilder;
    
    public function getImportedEventRepositoryBuilder()
    {
        return $this->importedEventRepositoryBuilder;
    }

    // ############################### optional controls; turn on and off
    
    protected $hasDateControls = true;
    
    public function getDateControls()
    {
        return $this->hasDateControls;
    }
    
    public function setHasDateControls($hasDateControls)
    {
        $this->hasDateControls = $hasDateControls;
    }

    // ############################### params
    
    protected $fromNow = true;
    protected $from;
    protected $includeSpecifiedUserAttending = true;
    protected $includeSpecifiedUserWatching = true;


    public function set($data)
    {
        if (isset($data['importedEventListFilterDataSubmitted'])) {
        
            // From
            if ($this->hasDateControls) {
                $fromNow = isset($data['fromNow']) ? $data['fromNow'] : 0;
                if (!$fromNow) {
                    $this->fromNow = false;
                    $from = isset($data['from']) ? strtolower(trim($data['from'])) : null;
                    if ($from) {
                        try {
                            $fromDT = new \DateTime($from, new \DateTimeZone('UTC'));
                            $fromDT->setTime(0, 0, 0);
                            $this->from = $fromDT->format('j F Y');
                        } catch (\Exception $e) {
                            // assume it's parse exception, ignore.
                        }
                    }
                }
            }
        }
        
        // apply to search
        if ($this->fromNow) {
            $this->importedEventRepositoryBuilder->setAfterNow();
        } elseif ($this->from) {
            $this->importedEventRepositoryBuilder->setAfter($fromDT);
        }
    }
    
    public function getFrom()
    {
        return $this->from;
    }
    public function getFromNow()
    {
        return $this->fromNow;
    }
}
