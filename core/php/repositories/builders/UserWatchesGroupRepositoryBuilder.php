<?php

namespace repositories\builders;

use models\GroupModel;
use models\UserWatchesGroupModel;
use models\UserAccountModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserWatchesGroupRepositoryBuilder extends BaseRepositoryBuilder
{
    protected $onlyCurrent = true;

    /** @var GroupModel **/
    protected $group;

    public function setGroup(GroupModel $group)
    {
        $this->group = $group;
        return $this;
    }

    /** @var UserAccountModel **/
    protected $user;
    
    public function setUser(UserAccountModel $user)
    {
        $this->user = $user;
        return $this;
    }
    
    protected function build()
    {
        if ($this->onlyCurrent) {
            $this->joins[] = " JOIN group_information ON group_information.id = user_watches_group_information.group_id  ";
            $this->joins[] = " LEFT JOIN user_watches_site_information ON user_watches_site_information.site_id = group_information.site_id ".
                            "AND user_watches_site_information.user_account_id = user_watches_group_information.user_account_id ".
                            " AND user_watches_site_information.is_watching = '1' ";
            $this->where[] = " user_watches_group_information.is_watching = '1' AND user_watches_site_information.is_watching IS NULL ";
        }

        if ($this->group) {
            $this->where[] = " user_watches_group_information.group_id = :group_id";
            $this->params['group_id'] = $this->group->getId();
        }
        
        if ($this->user) {
            $this->where[] = " user_watches_group_information.user_account_id = :user_account_id";
            $this->params['user_account_id'] = $this->user->getId();
        }
    }
    
    protected function buildStat()
    {
        $sql = "SELECT user_watches_group_information.* FROM user_watches_group_information ".
                implode(" ", $this->joins).
                ($this->where ? " WHERE ".implode(" AND ", $this->where) : "").
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
            $uwgm = new UserWatchesGroupModel();
            $uwgm->setFromDataBaseRow($data);
            $results[] = $uwgm;
        }
        return $results;
    }
}
