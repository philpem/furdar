<?php

namespace repositories;

use models\UserAccountModel;
use models\UserAccountGeneralSecurityKeyModel;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserAccountGeneralSecurityKeyRepository
{

    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    /**
     * This will always return something. If one doesn't exist, one will be created.
     * @return UserAccountPrivateFeedKeyModel
     */
    public function getForUser(UserAccountModel $user)
    {
        $stat = $this->app['db']->prepare("SELECT * FROM user_account_general_security_key WHERE user_account_id=:uid");
        $stat->execute(array('uid'=>$user->getId()));
        if ($stat->rowCount() > 0) {
            $uagskm = new UserAccountGeneralSecurityKeyModel();
            $uagskm->setFromDataBaseRow($stat->fetch());
            return $uagskm;
        }
        
        $uagskm = new UserAccountGeneralSecurityKeyModel();
        $uagskm->setUserAccountId($user->getId());
        $uagskm->setAccessKey(createKey(2, 150));
        
        // TODO check not already used
        
        $stat = $this->app['db']->prepare("INSERT INTO user_account_general_security_key (user_account_id, access_key, created_at) ".
                "VALUES (:user_account_id, :access_key, :created_at)");
        $stat->execute(array(
                'user_account_id'=>$uagskm->getUserAccountId(),
                'access_key'=>$uagskm->getAccessKey(),
                'created_at'=>$this->app['timesource']->getFormattedForDataBase()
            ));
        
        return $uagskm;
    }
    
    /** @return UserAccountGeneralSecurityKeyModel **/
    public function loadByUserAccountIDAndAccessKey(int $id, string $access)
    {
        $stat = $this->app['db']->prepare("SELECT user_account_general_security_key.* FROM user_account_general_security_key WHERE user_account_id =:user_account_id AND access_key=:access_key");
        $stat->execute(array( 'user_account_id'=>$id, 'access_key'=>$access ));
        if ($stat->rowCount() > 0) {
            $uagskm = new UserAccountGeneralSecurityKeyModel();
            $uagskm->setFromDataBaseRow($stat->fetch());
            return $uagskm;
        }
    }
}
