<?php

namespace db\migrations;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class Migration
{
    protected $id;
    protected $sql;
    protected $applied = false;


    public function __construct(string $id=null, string $sql=null)
    {
        $this->id = $id;
        $this->sql = $sql;
    }
    
    public function getId()
    {
        return $this->id;
    }
    public function getApplied()
    {
        return $this->applied;
    }
    public function setIsApplied()
    {
        $this->applied = true;
    }

    public function performMigration(\PDO $db, \TimeSource $timeSource, \Config $config)
    {
        foreach (explode(";", $this->sql) as $line) {
            if (trim($line)) {
                $db->query($line.';');
            }
        }
    }
    
    public function getIdAsUnixTimeStamp()
    {
        $year = substr($this->id, 0, 4);
        $month = substr($this->id, 4, 2);
        $day = substr($this->id, 6, 2);
        $hour = substr($this->id, 8, 2);
        $min = substr($this->id, 10, 2);
        $sec = substr($this->id, 12, 2);
        return mktime($hour, $min, $sec, $month, $day, $year);
    }
}
