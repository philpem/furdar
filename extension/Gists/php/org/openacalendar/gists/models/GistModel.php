<?php

namespace org\openacalendar\gists\models;

use org\openacalendar\gists\repositories\GistRepository;
use Silex\Application;

/**
 *
 * @link https://opentechcalendar.co.uk/ This is the software for Open Tech Calendar!
 * @link https://gitlab.com/opentechcalendar You will find it's source here!
 * @license https://gitlab.com/opentechcalendar/opentechcalendar/blob/master/LICENSE.txt 3-clause BSD
 * @copyright (c) JMB Technology Limited, https://www.jmbtechnology.co.uk/
 */

class GistModel
{
    protected $id;
    protected $site_id;
    protected $slug;
    protected $title;
    protected $created_at;
    protected $is_deleted;


    public function setFromDataBaseRow($data)
    {
        $this->id = $data['id'];
        $this->site_id = $data['site_id'];
        $this->slug = $data['slug'];
        $this->title = $data['title'];
        $utc = new \DateTimeZone("UTC");
        $this->created_at = new \DateTime($data['created_at'], $utc);
        $this->is_deleted = $data['is_deleted'];
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getSiteId()
    {
        return $this->site_id;
    }

    public function setSiteId($site_id)
    {
        $this->site_id = $site_id;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getSlugForUrl()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getIsDeleted()
    {
        return $this->is_deleted;
    }

    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;
    }
}
