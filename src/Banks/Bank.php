<?php 
/**
 * Luminova Framework
 *
 * @package Luminova
 * @author Ujah Chigozie Peter
 * @copyright (c) Nanoblock Technology Ltd
 * @license See LICENSE file
*/
namespace Luminova\ExtraUtils\Payment\Models;

use Luminova\ExtraUtils\Payment\Utils\Helper;
class Bank
{
    /**
     * bank code 
     * @var string $bankCode 
    */
    private string $bankCode = 'wema-bank';

    /**
     * customer code 
     * @var string $customerCode 
    */
    private string $customerCode = '';

    /**
     * Customer sub account
     * @var string $subAccount 
    */
    private $subAccount = '';

    /**
     * Customer split code
     * @var string $splitCode 
    */
    private $splitCode = '';

    /**
     * Customer first name
     * @var string $customerFirstName 
    */
    private $customerFirstName = '';

    /**
     * Customer last name
     * @var string $customerLastName 
    */
    private $customerLastName = '';

    /**
     * Customer phone number
     * @var string $customerPhone 
    */
    private $customerPhone = '';

    /**
     * Set bank code
     * @param string $bankCode 
     * 
     * @return self
    */
    final public function setBankCode(string $bankCode): self
    {
        $this->bankCode = $bankCode;
        
        return $this;
    }

    /**
     * Set customer code
     * 
     * @param string $customerCode 
     * 
     * @return self
    */
    final public function setCustomerCode(string $customerCode): self
    {
        $this->customerCode = $customerCode;
        
        return $this;
    }

    /**
     * Set customer sub account
     * @param string $subAccount 
     * 
     * @return self
    */
    final public function setSubAccount(string $subAccount): self
    {
        $this->subAccount = $subAccount;

        return $this;
    }

    /**
     * Set customer bank split code
     * @param string $splitCode 
     * 
     * @return self
    */
    final public function setSplitCode(string $splitCode): self
    {
        $this->splitCode = $splitCode;

        return $this;
    }

    /**
     * Set customer name
     * 
     * @param string $firstName 
     * @param string $lastName
     * 
     * @return self
    */
    final public function setCustomerName(string $firstName, string $lastName): self
    {
        $this->customerFirstName = $firstName;
        $this->customerLastName = $lastName;

        return $this;
    }

    /**
     * Set customer phone number
     * 
     * @param string $customerPhone 
     * 
     * @return self
    */
    final public function setCustomerPhone(string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }


    /**
     * Get bank code
     * 
     * @return string 
    */
    final public function getBankCode(): string
    {
        return $this->bankCode;
    }

    /**
     * Get customer code
     * 
     * @return string 
    */
    final public function getCustomerCode(): string
    {
        return $this->customerCode;
    }

    /**
     * Get customer split code
     * 
     * @return string 
    */
    final public function getSplitCode(): string
    {
        return $this->splitCode;
    }

    /**
     * Get customer sub account
     * 
     * @return string 
    */
    final public function getSubAccount(): string
    {
        return $this->subAccount;
    }

    /**
     * Get customer first name
     * 
     * @return string 
    */
    final public function getCustomerFirstName(): string
    {
        return $this->customerFirstName;
    }

    /**
     * Get customer last name
     * 
     * @return string 
    */
    final public function getCustomerLastName(): string
    {
        return $this->customerLastName;
    }

    /**
     * Get customer phone number
     * 
     * @return string 
    */
    final public function getCustomerPhone(): string
    {
        return $this->customerPhone;
    }


    /**
     * Convert properties to array
     * 
     * @param bool $camelCase Optional boolean indicating whether to use camel case
     * Default is false
     * 
     * @return string 
    */
    final public function toArray(bool $camelCase = false): array
    {
        $properties =  [
            'preferred_bank' => $this->bankCode,
            'customer' => $this->customerCode,
            'sub_account' => $this->subAccount,
            'split_code' => $this->splitCode,
            'first_name' => $this->customerFirstName,
            'last_name' => $this->customerLastName,
            'phone' => $this->customerPhone,
        ];

        if($camelCase){
            $properties = Helper::toArrayCamelCase($properties);
        }

        return $properties;
    }

    /**
     * Convert properties to object
     * 
     * @return string 
    */
    final public function toObject(bool $camelCase = false): object
    {
        $obj = (object) $this->toArray($camelCase);

        return $obj;
    }
}
