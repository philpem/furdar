<?php

namespace repositories\builders;

use models\EventCustomFieldDefinitionModel;
use models\SiteModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class EventCustomFieldDefinitionRepositoryBuilder extends BaseRepositoryBuilder
{


    /** @var SiteModel **/
    protected $site;

    public function setSite(SiteModel $site)
    {
        $this->site = $site;
    }


    protected function build()
    {
        $this->select[] = 'event_custom_field_definition_information.*';

        if ($this->site) {
            $this->where[] =  " event_custom_field_definition_information.site_id = :site_id ";
            $this->params['site_id'] = $this->site->getId();
        }
    }

    protected function buildStat()
    {
        $sql = "SELECT " . implode(", ", $this->select) . " FROM event_custom_field_definition_information ".
            implode(" ", $this->joins).
            ($this->where ? " WHERE ".implode(" AND ", $this->where) : '').
            " ORDER BY event_custom_field_definition_information.key ASC ".($this->limit > 0 ? " LIMIT ". $this->limit : "");

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
            $area = new EventCustomFieldDefinitionModel();
            $area->setFromDataBaseRow($data);
            $results[] = $area;
        }
        return $results;
    }
}
