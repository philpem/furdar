<?php

namespace repositories\builders;

use models\UserAccountModel;
use models\UserAccountVerifyEmailModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserAccountVerifyEmailRepositoryBuilder extends BaseRepositoryBuilder
{

    /** @var UserAccountModel **/
    protected $user;
    
    public function setUser(UserAccountModel $user)
    {
        $this->user = $user;
        return $this;
    }
    
    protected function build()
    {
        if ($this->user) {
            $this->where[] = " user_account_verify_email.user_account_id = :user_account_id";
            $this->params['user_account_id'] = $this->user->getId();
        }
    }
    
    protected function buildStat()
    {
        $sql = "SELECT user_account_verify_email.* FROM user_account_verify_email ".
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
            $uwgm = new UserAccountVerifyEmailModel();
            $uwgm->setFromDataBaseRow($data);
            $results[] = $uwgm;
        }
        return $results;
    }
}
