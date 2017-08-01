<?php

namespace Rebrandly\Model;

class Link
{
    private $id;

    private $title;

    private $slashtag;

    private $destination;

    private $shortUrl;

    private $domain;

    private $status;

    private $createdAt;

    private $updatedAt;

    private $clicks;

    private $lastClickAt;

    private $favourite;

    private $forwardParameters;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getSlashtag()
    {
        return $this->slashtag;
    }

    public function setSlashtag($slashtag)
    {
        $this->slashtag = $slashtag;
        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    public function setShortUrl($shortUrl)
    {
        $this->shortUrl = $shortUrl;
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain)
    {
        $this->domain = $domain;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getClicks()
    {
        return $this->clicks;
    }

    public function setClicks($clicks)
    {
        $this->clicks = $clicks;
        return $this;
    }

    public function getLastClickAt()
    {
        return $this->lastClickAt;
    }

    public function setLastClickAt($lastClickAt)
    {
        $this->lastClickAt = $lastClickAt;
        return $this;
    }

    public function getFavourite()
    {
        return $this->favourite ;
    }

    public function setFavourite($favourite)
    {
        $this->favourite = $favourite ;
        return $this;
    }

    public function getForwardParameters()
    {
        return $this->forwardParameters;
    }

    public function setForwardParameters($forwardParameters)
    {
        $this->forwardParameters = $forwardParameters;

        return $this;
    }

    public function __construct($destination = ' ')
    {
        $this->setdestination($destination);

        return $this;
    }
}
