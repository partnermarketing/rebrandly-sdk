<?php

namespace Rebrandly\Model;

class Link
{
    private $id;

    private $destination;

    private $slashTag;

    private $description;

    private $domain;

    private $createdAt;

    private $updatedAt;

    private $favourite ;

    private $title;

    private $linkId;

    private $status;

    private $tags;

    private $scripts;

    private $clicks;

    private $isprivate;

    private $shortUrl;

    private $domainId;

    private $domainName;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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

    public function getSlashTag()
    {
        return $this->slashTag;
    }

    public function setSlashTag($slashTag)
    {
        $this->slashTag = $slashTag;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description )
    {
        $this->description = $description;
        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;
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

    public function getFavourite()
    {
        return $this->favourite ;
    }

    public function setFavourite($favourite)
    {
        $this->favourite = $favourite ;
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

    public function getLinkId()
    {
        return $this->linkId;
    }

    public function setLinkId($linkId)
    {
        $this->linkId = $linkId;
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

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getScripts()
    {
        return $this->scripts;
    }

    public function setScripts($scripts)
    {
        $this->scripts = $scripts;
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

    public function getIsPrivate()
    {
        return $this->isPrivate;
    }

    public function setIsPrivate($isPrivate)
    {
        $this->isPrivate = $isPrivate;
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

    public function getDomainId()
    {
        return $this->domainId;
    }

    public function setDomainId($domainId)
    {
        $this->domainId = $domainId;
        return $this;
    }

    public function getDomainName()
    {
        return $this->domainName;
    }

    public function setDomainName($domainName)
    {
        $this->domainName = $domainName;
        return $this;
    }

    public function __construct($destination = ' ')
    {
        $this->setdestination($destination);

        return $this;
    }
}
