<?php

namespace Rebrandly\Model;

class Account
{
    private $id;

    private $username;

    private $email;

    private $fullName;

    private $avatarUrl;

    private $createdAt;

    private $subscription;

    public function getId()
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->username;
    }

    public function getcreatedAt()
    {
        return $this->createdAt;
    }

    public function getsubscription()
    {
        return $this->subscription;
    }

    /**
     * Turns a account-like accountArray into an actual account object
     *
     * @param array $accountArray An array containing all data to be assigned into the
     *    account
     *
     * @return void
     */
    public function import($accountArray)
    {
        foreach ($accountArray as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Turns a account into a account-like array
     *
     * @return array $account An array with all properties from the account
     */
    public function export()
    {
        $exportFields = [
            'id', 'username', 'email', 'fullName', 'avatarUrl',
            'createdAt'
        ];

        $accountArray= [];
        foreach ($exportFields as $fieldName) {
            $getter = 'get' . $fieldName;
            $value = $this->$getter();
            if ($value) {
                $accountArray[$fieldName] = $value;
            }
        }
        return $accountArray;
    }
}
