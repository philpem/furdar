<?php

namespace org\openacalendar\gists\repositories\builders;

use org\openacalendar\gists\models\GistContentModel;
use org\openacalendar\gists\models\GistModel;
use repositories\builders\BaseRepositoryBuilder;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class GistContentRepositoryBuilder extends BaseRepositoryBuilder
{

    /** @var GistModel **/
    protected $gist;

    /**
     * @param GistModel $gist
     */
    public function setGist($gist)
    {
        $this->gist = $gist;
    }
    
    protected function build()
    {
        $this->select[] = ' gist_content_information.* ';

        if ($this->gist) {
            $this->where[] =  " gist_content_information.gist_id = :gist_id ";
            $this->params['gist_id'] = $this->gist->getId();
        }
    }

    protected function buildStat()
    {
        global $DB;


        $sql = "SELECT ".  implode(",", $this->select)." FROM gist_content_information ".
            implode(" ", $this->joins).
            ($this->where ? " WHERE ".implode(" AND ", $this->where) : '').
            " ORDER BY gist_content_information.sort ASC ";

        $this->stat = $DB->prepare($sql);
        $this->stat->execute($this->params);
    }


    public function fetchAll()
    {
        $this->buildStart();
        $this->build();
        $this->buildStat();



        $results = array();
        while ($data = $this->stat->fetch()) {
            $gistContentModel = new GistContentModel();
            $gistContentModel->setFromDataBaseRow($data);
            $results[] = $gistContentModel;
        }
        return $results;
    }
}
