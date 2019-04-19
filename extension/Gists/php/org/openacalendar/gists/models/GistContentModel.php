<?php

namespace org\openacalendar\gists\models;

use models\EventModel;
use repositories\EventRepository;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class GistContentModel
{
    protected $id;
    protected $gist_id;
    protected $sort = 0;
    protected $event_id;
    protected $group_id;
    protected $area_id;
    protected $venue_id;
    protected $content_title;
    protected $content_text;

    /** @var  EventModel */
    protected $event;

    public function setFromDataBaseRow($data)
    {
        $this->id = $data['id'];
        $this->gist_id = $data['gist_id'];
        $this->sort = $data['sort'];
        $this->event_id = $data['event_id'];
        $this->group_id = $data['group_id'];
        $this->area_id = $data['area_id'];
        $this->venue_id = $data['venue_id'];
        $this->content_title = $data['content_title'];
        $this->content_text = $data['content_text'];
    }

    public function loadModels(Application $application)
    {
        if ($this->event_id) {
            $eventRepo = new EventRepository($application);
            $this->event = $eventRepo->loadByID($this->event_id);
        }
    }

    /**
     * @return mixed
     */
    public function getAreaId()
    {
        return $this->area_id;
    }

    /**
     * @param mixed $area_id
     */
    public function setAreaId($area_id)
    {
        $this->area_id = $area_id;
    }

    /**
     * @return mixed
     */
    public function getContentText()
    {
        return $this->content_text;
    }

    /**
     * @return boolean
     */
    public function hasContentText()
    {
        return (boolean)$this->content_text;
    }

    /**
     * @param mixed $content_text
     */
    public function setContentText($content_text)
    {
        $this->content_text = $content_text;
    }

    /**
     * @return boolean
     */
    public function hasContentTitle()
    {
        return (boolean)$this->content_title;
    }

    /**
     * @return mixed
     */
    public function getContentTitle()
    {
        return $this->content_title;
    }

    /**
     * @param mixed $content_title
     */
    public function setContentTitle($content_title)
    {
        $this->content_title = $content_title;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * @return boolean
     */
    public function hasEvent()
    {
        return (boolean)$this->event;
    }

    /**
     * @param mixed $event_id
     */
    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
    }

    /**
     * @return mixed
     */
    public function getGistId()
    {
        return $this->gist_id;
    }

    /**
     * @param mixed $gist_id
     */
    public function setGistId($gist_id)
    {
        $this->gist_id = $gist_id;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @param mixed $group_id
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getVenueId()
    {
        return $this->venue_id;
    }

    /**
     * @param mixed $venue_id
     */
    public function setVenueId($venue_id)
    {
        $this->venue_id = $venue_id;
    }

    /**
     * @return EventModel
     */
    public function getEvent()
    {
        return $this->event;
    }
}
