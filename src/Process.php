<?php

/**
 * Luminova Framework
 *
 * @package Luminova
 * @author Ujah Chigozie Peter
 * @copyright (c) Nanoblock Technology Ltd
 * @license See LICENSE file
*/
namespace Luminova\ExtraUtils\Payment;
use Luminova\ExtraUtils\Payment\PaymentException;
use Luminova\ExtraUtils\Payment\Network;

class Process  {
    /**
    *
    * @var string base api url 
    */
    private string $apiBase = '';

    /**
    *
    * @var Network network request instance 
    */
    private Network $network;


    /**
    * Initializes process instance 
    * @param string $key api key
    * @param string $base api base url 
    */
    public function __construct(string $key, string $base){
        $this->apiBase = $base;
        $this->network = new Network($key);
    }

    /**
    * Set base api url 
    * @param string $base api base url 
    * 
    * @return self $this
    */
    public function setBase(string $base): self {
        $this->apiBase = $base;
        return $this;
    }

   /**
    * Find customer account 
    *
    *@param string $customer Customer email or id code
    *
    * @return object 
    * @throws PaymentException
   */
   public function findCustomer(string $customer): object 
   {
        $url = "{$this->apiBase}/customer/" . $customer;
        $request = $this->network->request($url, "GET", null, true);

        return $this->returnResult($request);
   }

   /**
    * Create customer account 
    *
    *@param array $fields Customer fields
    *
    * @return object 
    * @throws PaymentException
   */
   public function createCustomer(array $fields = []): object 
   {
        $url = "{$this->apiBase}/customer";
        $request = $this->network->request($url, 'POST', $fields, false);

        return $this->returnResult($request);
   }

   /**
    * Verify payment reference 
    *
    *@param string $reference payment reference
    *
    * @return object 
    * @throws PaymentException
   */
   public function verifyPayment(string $reference): object 
   {
        $url = "{$this->apiBase}/transaction/verify/" . $reference;
        $request = $this->network->request($url, "GET", null, false);

        return $this->returnResult($request);
   }

   /**
    * Charge authorization 
    *
    *@param array $fields filed 
    *
    * @return object 
    * @throws PaymentException
   */
   public function chargeAuthorization(array $fields = []): object 
   {
        $url = "{$this->apiBase}/transaction/charge_authorization";
        $request = $this->network->request($url, 'POST', $fields, false);

        return $this->returnResult($request);
   }


  /**
    * Resolve account number
    *
    * @param string|int $account account number 
    * @param string|int $bic bank code 
    *
    * @return object 
    * @throws PaymentException
   */
   public function resolveAccountNumber(string|int $account, string|int $bic): object 
   {
        $url = "{$this->apiBase}/bank/resolve?account_number=".$account."&bank_code=".$bic;
        $request = $this->network->request($url, "GET", null, true);

        return $this->returnResult($request);
   }

   /**
    * Resolve BVN number
    *
    * @param string|int $bvn Bank Verification Number (BVN)
    *
    * @return object 
    * @throws PaymentException
   */
   public function resolveBvn(string|int $bvn): object 
   {
        $url = "{$this->apiBase}/bank/resolve_bvn/".$bvn;
        $request = $this->network->request($url, "GET", null, true);

        return $this->returnResult($request);
   }

   /**
    * Create transfer recipient
    *
    * @param array $fields fields
    *
    * @return object 
    * @throws PaymentException
   */
   public function createRecipient(array $fields = []): object 
   {
        if($fields === []){
            $fields = [
            'type' => null,
            'name' => null,
            'account_number' => null,
            'bank_code' => null,
            'currency' => null
            ];
        }
        $url = "{$this->apiBase}/transferrecipient";
        $request = $this->network->request($url, 'POST', $fields, false);

        return $this->returnResult($request);
   }

   /**
    * Execute transfer
    *
    * @param array $fields fields
    *
    * @return object 
    * @throws PaymentException
   */
   public function executeTransfer(array $fields = []): object 
   {
      if($fields === []){
         $fields = [
            'source' => null,
            'amount' => 0,
            'recipient' => null,
            'reason' => null
         ];
      }
      $url = "{$this->apiBase}/transfer";
      $request = $this->network->request($url, 'POST', $fields, false);
     
      return $this->returnResult($request);
   }

   /**
    * List country banks
    *
    * @return object 
    * @throws PaymentException
   */
   public function listBank(): object 
   {
        $url = "{$this->apiBase}/bank";
        $request = $this->network->request($url, "GET", null, true);

        return $this->returnResult($request);
   }

   /**
    * Execute transfer
    *
    * @param string|int $code bank code id
    *
    * @return object 
    * @throws PaymentException
   */
   public function getBankByCode(string|int $code): object{

        $banks = $this->listBank();
        $request = (object)[];
        foreach($banks->data as $value){
            if($value->code === $code){
            $request = $value;
            break;
            }
        }
        return $request;
   }

   /**
    * Execute transfer
    *
    * @param string $name bank name
    *
    * @return object 
    * @throws PaymentException
   */
   public function getBankByName(string $name){
        $banks = $this->listBank();
        $request = (object)[];
        foreach($banks->data as $value){
            if(strtolower($value->name) === strtolower($name)){
            $request = $value;
            break;
            }
        }
        return $request;
   }

   /**
    * Return response from server
    *
    * @param array $result response
    *
    * @return object 
   */
  private function returnResult(array $result): object 
    {
        if (!empty($result['error'])) {
            throw new PaymentException($result['error']);
        }

        if (empty($result['success'])) {
            throw new PaymentException("Something went wrong");
        }

        if (!$result['success']['status']) {
            throw new PaymentException(($result['success']['message'] ?? "Transfer was not successful"));
        }

        return (object) $result['success'];
    }

}