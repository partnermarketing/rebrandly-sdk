<?php

namespace Rebrandly\Model;

/**
 * Domain Model
 *
 * Stores and recalls information about a Rebrandly as described at
 * https://developers.rebrandly.com/docs/branded-domain-model
 */
class Domain
{
    /**
     * @var string Unique ID, regularly used as a lookup key for a domain
     */
    private $id;

    /**
     * @var string Full domain name
     */
    private $fullName;

    /**
     * @var string TLD of the domain
     */
    private $topLevelDomain;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var string user|service Whether the domain belongs to a shortening
     *    service such as Rebrandly, or belongs to the user
     */
    private $type;

    /**
     * @var bool Whether the domain is available for use when shortening URLs
     */
    private $active;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getTopLevelDomain()
    {
        return $this->topLevelDomain;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Turns a domain-like domainArray into an actual domain object
     *
     * @param array $domainArray An array containing all data to be assigned into the
     *    domain
     *
     * @return void
     */
    static function import($domainArray)
    {
        $domain = new Domain;

        foreach ($domainArray as $key => $value) {
            $domain->$key = $value;
        }

        return $domain;
    }

    /**
     * Turns a domain into a domain-like array
     *
     * @return array $domain An array with all properties from the domain
     */
    public function export()
    {
        $exportFields = [
            'id', 'fullName', 'topLevelDomain', 'createdAt', 'updatedAt',
            'type', 'active'
        ];

        $domainArray= [];
        foreach ($exportFields as $field) {
            $value = $this->$field;
            if ($value) {
                $domainArray[$field] = $value;
            }
        }
        return $domainArray;
    }
}
