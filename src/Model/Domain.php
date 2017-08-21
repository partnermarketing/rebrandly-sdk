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
    private $id;

    private $fullName;

    private $topLevelDomain;

    private $createdAt;

    private $updatedAt;

    private $type;

    private $active;

    public function getId()
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function getTopLevelDomain()
    {
        return $this->topLevelDomain;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->createdAt;
    }

    public function getType()
    {
        return $this->type;
    }

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
    public function import($domainArray)
    {
        foreach ($domainArray as $key => $value) {
            $this->$key = $value;
        }
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
