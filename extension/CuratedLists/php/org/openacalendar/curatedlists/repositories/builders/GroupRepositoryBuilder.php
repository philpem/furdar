<?php

namespace org\openacalendar\curatedlists\repositories\builders;

use org\openacalendar\curatedlists\models\CuratedListModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GroupRepositoryBuilder extends \repositories\builders\GroupRepositoryBuilder
{

    /** @var CuratedListModel */
    protected $curatedList;

    /**
     * @param CuratedListModel $curatedList
     */
    public function setCuratedList($curatedList)
    {
        $this->curatedList = $curatedList;
    }

    protected function build()
    {
        parent::build();

        if ($this->curatedList) {
            $this->joins[] = " JOIN group_in_curated_list ON group_in_curated_list.group_id = group_information.id AND group_in_curated_list.removed_at IS NULL AND group_in_curated_list.curated_list_id = :curated_list_id ";
            $this->params['curated_list_id'] = $this->curatedList->getId();
        }
    }
}
