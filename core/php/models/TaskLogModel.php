<?php


namespace models;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class TaskLogModel
{
    protected $extension_id;
    protected $task_id;
    /** @var  \DateTime */
    protected $started_at;
    /** @var  \DateTime */
    protected $ended_at;
    protected $result_data;
    protected $exception_data;



    public function setFromDataBaseRow($data)
    {
        $this->extension_id = $data['extension_id'];
        $this->task_id = $data['task_id'];

        $utc = new \DateTimeZone("UTC");
        $this->started_at = new \DateTime($data['started_at'], $utc);
        $this->ended_at = $data['ended_at'] ? new \DateTime($data['ended_at'], $utc) : null;
        $this->result_data = $data['result_data'] ? json_decode($data['result_data']) : null;
        $this->exception_data = $data['exception_data'] ? json_decode($data['exception_data']) : null;
    }

    /**
     * @return \DateTime
     */
    public function getEndedAt()
    {
        return $this->ended_at;
    }

    /**
     * @return mixed
     */
    public function getExtensionId()
    {
        return $this->extension_id;
    }

    /**
     * @return mixed
     */
    public function getResultData()
    {
        return $this->result_data;
    }

    /**
     * @return \DateTime
     */
    public function getStartedAt()
    {
        return $this->started_at;
    }

    /**
     * @return mixed
     */
    public function getExceptionData()
    {
        return $this->exception_data;
    }

    public function hasExceptionData()
    {
        return (boolean)$this->exception_data;
    }

    /**
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    public function getIsResultDataHaveKey($key)
    {
        if (!$this->result_data) {
            return false;
        }
        return property_exists($this->result_data, $key);
    }

    public function getResultDataValue($key)
    {
        if (property_exists($this->result_data, $key)) {
            $vars = get_object_vars($this->result_data);
            return $vars[$key];
        }
    }
}
