<?php

namespace repositories\builders;

use models\ImportedEventModel;
use models\SiteModel;
use models\EventModel;
use models\GroupModel;
use models\TagModel;
use models\VenueModel;
use models\UserAccountModel;
use models\CountryModel;
use models\ImportModel;
use models\AreaModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class ImportedEventRepositoryBuilder extends BaseRepositoryBuilder
{
    protected $orderBy = " start_at ";
    protected $orderDirection = " ASC ";

    /** @var SiteModel **/
    protected $site;
    
    public function setSite(SiteModel $site)
    {
        $this->site = $site;
    }


    /** @var ImportModel **/
    protected $import;
    
    public function setImport(ImportModel $import)
    {
        $this->import = $import;
    }

    
    /** @var \DateTime **/
    protected $after;
    
    public function setAfter(\DateTime $a)
    {
        $this->after = $a;
        return $this;
    }
    
    public function setAfterNow()
    {
        $this->after = $this->app['timesource']->getDateTime();
        return $this;
    }


    /**
     * This does not actually do anything yet ... but when the day comes that it does, there is calling code that needs to make sure it's set to a specific value.
     * So best to code setting that value in the calling code now.
     */
    public function setIncludeDeleted($value)
    {
        // TODO
    }

    protected function build()
    {
        $this->select[] = 'imported_event.*';

        if ($this->site) {
            $this->joins[] = " JOIN import_url_information ON imported_event.import_url_id = import_url_information.id ";
            $this->where[] =  " import_url_information.site_id = :site_id ";
            $this->params['site_id'] = $this->site->getId();
        }

        if ($this->import) {
            $this->where[] =  " imported_event.import_url_id = :import_url_id ";
            $this->params['import_url_id'] = $this->import->getId();
        }

        if ($this->after) {
            $this->where[] = ' imported_event.end_at > :after';
            $this->params['after'] = $this->after->format("Y-m-d H:i:s");
        }
    }
    
    protected function buildStat()
    {
        $sql = "SELECT ".  implode(",", $this->select)." FROM imported_event ".
                implode(" ", $this->joins).
                ($this->where ? " WHERE ".implode(" AND ", $this->where) : "").
                " ORDER BY  ".$this->orderBy." ".$this->orderDirection .($this->limit > 0 ? " LIMIT ". $this->limit : "");

        $this->stat = $this->app['db']->prepare($sql);
        $this->stat->execute($this->params);
    }
    
    
    public function fetchAll()
    {
        $this->buildStart();
        $this->build();
        $this->buildStat();
        

        $results = array();
        while ($data = $this->stat->fetch()) {
            $event = new ImportedEventModel();
            $event->setFromDataBaseRow($data);
            $results[] = $event;
        }
        return $results;
    }
}
