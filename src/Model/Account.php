<?php

namespace Rebrandly\Model;

/**
 * Account Model
 *
 * Stores and recalls information about a Rebrandly account as described at
 * https://developers.rebrandly.com/docs/account-model
 */
class Account
{
    private $id;

    private $username;

    private $email;

    private $fullName;

    private $avatarUrl;

    private $createdAt;

    private $subscription;

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getFullName()
    {
        return $this->username;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSubscription()
    {
        return $this->subscription;
    }

    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Turns a account-like accountArray into an actual account object
     *
     * @param array $accountArray An array containing all data to be assigned into the
     *    account
     *
     * @return void
     */
    static function import($accountArray)
    {
        $account = new Account;

        foreach ($accountArray as $key => $value) {
            $account->$key = $value;
        }

        return $account;
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
            $value = $this->$fieldName;
            if ($value) {
                $accountArray[$fieldName] = $value;
            }
        }
        return $accountArray;
    }
}
