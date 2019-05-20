<?php

namespace repositories\builders;

use models\SiteModel;
use models\UserGroupModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserGroupRepositoryBuilder extends BaseRepositoryBuilder
{


    /** @var SiteModel **/
    protected $site;

    public function setSite(SiteModel $site)
    {
        $this->site = $site;
    }


    protected $index_only = false;

    /**
     * @param boolean $index_only
     */
    public function setIndexOnly($index_only)
    {
        $this->index_only = $index_only;
    }



    protected $include_deleted = true;

    public function setIncludeDeleted($value)
    {
        $this->include_deleted = $value;
    }


    protected function build()
    {
        if ($this->site) {
            $this->joins[] = " JOIN user_group_in_site ON user_group_in_site.user_group_id = user_group_information.id ".
                " AND user_group_in_site.site_id = :site_id AND user_group_in_site.removed_at IS NULL";
            $this->params['site_id'] = $this->site->getId();
        }

        if (!$this->include_deleted) {
            $this->where[] = " user_group_information.is_deleted = '0' ";
        }

        if ($this->index_only) {
            $this->where[] = " user_group_information.is_in_index = '1' ";
        }
    }

    protected function buildStat()
    {
        $sql = "SELECT user_group_information.* FROM user_group_information ".
            implode(" ", $this->joins).
            ($this->where ? " WHERE ".implode(" AND ", $this->where) : '').
            " ORDER BY user_group_information.title ASC ".
            ($this->limit > 0 ? " LIMIT ". $this->limit : "");

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
            $userGroup = new UserGroupModel();
            $userGroup->setFromDataBaseRow($data);
            $results[] = $userGroup;
        }
        return $results;
    }
}
