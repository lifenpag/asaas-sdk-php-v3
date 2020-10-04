<?php declare(strict_types = 1);

namespace LifenPag\Asaas\V3\Entities;

use LifenPag\Asaas\V3\Traits\Customer as CustomerTrait;

class Customer extends Entity
{
    use CustomerTrait;

    public const FIELDS_REQUIRED = ['name'];

    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $dateCreated;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $company;

    /**
     * @var string
     */
    public $phone;

    /**
     * @var string
     */
    public $mobilePhone;

    /**
     * @var string
     */
    public $address;

    /**
     * @var string
     */
    public $addressNumber;

    /**
     * @var string
     */
    public $complement;

    /**
     * @var string
     */
    public $province;

    /**
     * @var bool
     */
    public $foreignCustomer;

    /**
     * @var bool
     */
    public $notificationDisabled;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $cpfCnpj;

    /**
     * @var string
     */
    public $personType;

    /**
     * @var array
     */
    public $subscriptions = [];

    /**
     * @var array
     */
    public $payments = [];

    /**
     * @var array
     */
    public $notifications = [];

    /**
     * @var bool
     */
    public $deleted = [];

    /**
     * Set a Date Created
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = self::convertDateTime($dateCreated);
    }
}
