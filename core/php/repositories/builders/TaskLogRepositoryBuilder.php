<?php

namespace repositories\builders;

use models\TaskLogModel;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TaskLogRepositoryBuilder extends BaseRepositoryBuilder
{


    /** @var  TaskLogModel */
    protected $task;

    /**
     * @param \BaseTask $task
     */
    public function setTask(\BaseTask $task)
    {
        $this->task = $task;
    }

    protected function build()
    {
        if ($this->task) {
            $this->where[] = " task_log.extension_id = :extension_id AND task_log.task_id = :task_id ";
            $this->params['task_id'] = $this->task->getTaskId();
            $this->params['extension_id'] = $this->task->getExtensionId();
        }
    }

    protected function buildStat()
    {
        $sql = "SELECT task_log.* FROM task_log ".
                implode(" ", $this->joins).
                ($this->where?" WHERE ".implode(" AND ", $this->where):"").
                " ORDER BY task_log.started_at DESC ".($this->limit > 0 ? " LIMIT ". $this->limit : "");

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
            $task = new TaskLogModel();
            $task->setFromDataBaseRow($data);
            $results[] = $task;
        }
        return $results;
    }
}
