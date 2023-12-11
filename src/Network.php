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

class Network {
   /**
    * @var string $authorizationBearer header bearer token
   */
   private $authorizationBearer = '';

   /**
    * @param string $auth auth bearer token
   */
   public function __construct(string $auth){
      $this->authorizationBearer = $auth; 
   }

   /**
    * Send network request 
    * @param string $url api endpoint
    * @param string $method api request method 
    * @param array $param api request parameters
    * @param bool $extra 
    *
    * @return array response from server
    * @throws PaymentException
   */
   public function request(string $url, string $method = 'GET', ?array $param = null, bool $extras = false): array 
   {
      $method = strtoupper($method);
      $postField = '';
      if (!in_array($method, ['GET', 'POST'])) {
         throw new PaymentException('Invalid request method. Supported methods: GET, POST.');
      }

      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_HEADER, true);
      if($extras){
         curl_setopt($curl,CURLOPT_ENCODING, '');
         curl_setopt($curl,CURLOPT_MAXREDIRS, 10);
         curl_setopt($curl,CURLOPT_TIMEOUT, 30);
         curl_setopt($curl,CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
      }

    
      if($method === 'GET'){
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
         if($param !== null && $param !== []){
            $postField = http_build_query($param);
         }
      }else{
         curl_setopt($curl,CURLOPT_POST, true);
         if($param !== null && $param !== []){
            $postField = json_encode($param);
            $headers['Content-Type'] = 'application/json';
         }
      }

      if($postField !== ''){
         curl_setopt($curl, CURLOPT_POSTFIELDS, $postField);
      }

      curl_setopt($curl, CURLOPT_HTTPHEADER, [
         'Authorization: Bearer ' . $this->authorizationBearer,
         "Cache-Control: no-cache",
      ]);

      $response = curl_exec($curl);
      $error = [];
      
      if ($response === false) {
         $response = [];
         $error = curl_error($curl);
      }

      curl_close($curl);
      return [
         "error" => $error, 
         "success" => $response
      ];
   }
}
