<?php

namespace repositories\builders;

use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
abstract class BaseRepositoryBuilder
{


    /** @var Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }


    protected $limit = 0;
    
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    protected $where = array();
    protected $select = array();
    protected $joins = array();
    protected $params = array();

    protected function buildStart()
    {
        $this->where = array();
        $this->select = array();
        $this->joins = array();
        $this->params = array();
    }
    
    abstract protected function build();
    
    protected $stat;
    
    abstract protected function buildStat();
    
    abstract public function fetchAll();
}
