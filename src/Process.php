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

    private array $headers = [];


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
    * Set payment merchant name
    * @param string $name 
    * 
    * @return self $this
    */
    public function setProcessor(string $name): self {
        $this->network->setMerchantName($name);
        return $this;
    }


    /**
    * Get response headers
    * 
    * @return array $this->headers
    */
    public function getHeaders(): array 
    {
        return $this->headers;
    }

   /**
    * Find customer account 
    *
    *@param string $customer Customer email or code
    *
    * @return object 
    * @throws PaymentException
   */
   public function findCustomer(string $customer): object 
   {
        $url = "{$this->apiBase}/customer/{$customer}";
        $request = $this->network->request($url, "GET", null, true);

        return $this->returnResult($request);
   }

    /**
    * List customers 
    *
    * @param int $limit limit number of customers
    *
    * @return object 
    * @throws PaymentException
    */
    public function listCustomers(int $limit = 10): object 
    {
        $url = "{$this->apiBase}/customer";
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
    * Update customer account 
    *
    * @param array $code Customer code
    * @param array $fields Customer fields
    *
    * @return object 
    * @throws PaymentException
    */
    public function updateCustomer(string $code, array $fields): object 
    {
        $url = "{$this->apiBase}/customer/{$code}";
        $request = $this->network->request($url, 'POST', $fields, false);

        return $this->returnResult($request);
    }

    /**
    * Validate customer account 
    *
    * @param array $code Customer code
    * @param array $fields Customer fields assistive array 
    *
    * @return object 
    * @throws PaymentException
    */
    public function verifyCustomer(string $code, array $fields): object 
    {

        $url = "{$this->apiBase}/customer/{$code}/identification";
        $request = $this->network->request($url, 'POST', $fields, false);

        return $this->returnResult($request);
    }

    /**
    * Flag customer account 
    *
    * @param array $code Customer code
    * @param string $risk Customer risk action code
    *
    * @return object 
    * @throws PaymentException
    */
    public function flagCustomer(string $code, string $risk): object 
    {
        $risk = strtolower($risk);
        $riskActions = [
            'default', 'allow', 'deny'
        ];
        if(in_array($risk, $riskActions)){
            $fields = [
                'customer' => $code,
                'risk_action' => $risk
            ];
            $url = "{$this->apiBase}/customer/set_risk_action";
            $request = $this->network->request($url, 'POST', $fields, false);

            return $this->returnResult($request);
        }

        throw new PaymentException('Invalid risk action. Supported risk actions: default, allow, deny.');
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
        if ($result['error'] !== []) {
            throw new PaymentException($result['error']);
        }

        $body = $result['body'] ?? '';
        $contents = $body;

        if ($body === '') {
            throw new PaymentException("Something went wrong");
        }

        //if (is_string($body) &&  strpos($result['headers']['Content-Type'], 'application/json') !== false) {
        $this->headers = $result['headers'];
        if (is_string($body) &&  strpos($body, '{') !== false) {
            $contents = json_decode($body);
            if ($contents === null) {
                if(json_last_error() === JSON_ERROR_NONE){
                    $contents = [
                        'status' => false,
                        'message' =>  json_last_error_msg(),
                        'response' => $body
                    ];
                }
            }
        }

        /*if (!$contents->status) {
            throw new PaymentException(($contents->message ?? "Transfer was not successful"));
        }*/

        return  (object) $contents;
    }

}
