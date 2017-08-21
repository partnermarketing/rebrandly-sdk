<?php

namespace Rebrandly\Model;

use Rebrandly\Model\Domain as DomainModel;

/**
 * Link Model
 *
 * Stores and recalls information about a Rebrandly link as described at
 * https://developers.rebrandly.com/docs/link-entity
 */
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

    private $description;

    public function getClicks()
    {
        return $this->clicks;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function getFavourite()
    {
        return $this->favourite;
    }

    public function getForwardParameters()
    {
        return $this->forwardParameters;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLastClickAt()
    {
        return $this->lastClickAt;
    }

    public function getShortUrl()
    {
        return $this->shortUrl;
    }

    public function getSlashtag()
    {
        return $this->slashtag;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setDescription($description)
    {
        if (!is_string($description)) {
            $type = gettype($description);
            $errorText = printf('Expected description to be string, %s supplied', $type);
            throw new \InvalidArgumentException($errorText);
        }
        $this->description = $description;

        return $this;
    }

    public function setDestination($destination)
    {
        if (!is_string($destination)) {
            $type = gettype($destination);
            $errorText = printf('Expected destination to be string, %s supplied', $type);
            throw new \InvalidArgumentException($errorText);
        }
        $this->destination = $destination;
        return $this;
    }

    public function setDomain(DomainModel $domain)
    {
        if (!$domain instanceof Domain) {
            $type = gettype($domain);
            $errorText = printf('Expected domain to be an instance of a DomainModel, %s supplied', $type);
            throw new \InvalidArgumentException($errorText);
        }
        $this->domain = $domain;
        return $this;
    }

    public function setFavourite($favourite)
    {
        if (!is_bool($favourite)) {
            $type = gettype($favourite);
            $errorText = printf('Expected favourite to be boolean, %s supplied', $type);
            throw new \InvalidArgumentException($errorText);
        }
        $this->favourite = $favourite ;
        return $this;
    }

    public function setSlashtag($slashtag)
    {
        if (!is_string($slashtag)) {
            $type = gettype($slashtag);
            $errorText = printf('Expected slashtag to be string, %s supplied', $type);
            throw new \InvalidArgumentException($errorText);
        }
        $this->slashtag = $slashtag;
        return $this;
    }

    public function setTitle($title)
    {
        if (!is_string($title)) {
            $type = gettype($title);
            $errorText = printf('Expected title to be string, %s supplied', $type);
            throw new \InvalidArgumentException($errorText);
        }
        $this->title = $title;
        return $this;
    }

    /**
     * Turns a link-like linkArray into an actual link object
     *
     * @param array $linkArray An array containing all data to be assigned into the
     *    link
     *
     * @return void
     */
    public function import($linkArray)
    {
        foreach ($linkArray as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Turns a link into a link-like array
     *
     * @return array $link An array with all properties from the link
     */
    public function export()
    {
        $exportFields = [
            'id', 'title', 'slashtag', 'destination', 'shortUrl', 'domain',
            'status', 'createdAt', 'updatedAt', 'clicks', 'lastClickAt',
            'favourite', 'forwardParameters'
        ];

        $linkArray= [];
        foreach ($exportFields as $fieldName) {
            $value = $this->$fieldName;
            if ($value) {
                $linkArray[$fieldName] = $value;
            }
        }
        return $linkArray;
    }
}
