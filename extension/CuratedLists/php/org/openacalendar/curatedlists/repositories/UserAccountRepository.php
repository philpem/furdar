<?php


namespace org\openacalendar\curatedlists\repositories;

use org\openacalendar\curatedlists\models\CuratedListModel;
use models\UserAccountModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class UserAccountRepository extends \repositories\UserAccountRepository
{
    public function loadByOwnerOfCuratedList(CuratedListModel $curatedList)
    {
        global $DB;
        $stat = $DB->prepare("SELECT user_account_information.* FROM user_account_information ".
                " JOIN user_in_curated_list_information ON user_in_curated_list_information.user_account_id = user_account_information.id ".
                "WHERE user_in_curated_list_information.curated_list_id = :id AND user_in_curated_list_information.is_owner = 't'");
        $stat->execute(array( 'id'=>$curatedList->getId() ));
        if ($stat->rowCount() > 0) {
            $user = new UserAccountModel();
            $user->setFromDataBaseRow($stat->fetch());
            return $user;
        }
    }
}
