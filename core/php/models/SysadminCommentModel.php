<?php

namespace models;

use repositories\builders\EventRepositoryBuilder;
use Silex\Application;
use repositories\SiteRepository;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */
class SysadminCommentModel
{
    protected $id;
    protected $comment;

    /** @var DateTime **/
    protected $created_at;

    protected $user_account_username;
    
    public function setFromDataBaseRow($data)
    {
        $this->id = $data['id'];
        $this->comment = $data['comment'];
        $utc = new \DateTimeZone("UTC");
        $this->created_at = new \DateTime($data['created_at'], $utc);
        $this->user_account_username = $data['user_account_username'];
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return mixed
     */
    public function getUserAccountUsername()
    {
        return $this->user_account_username;
    }
}
