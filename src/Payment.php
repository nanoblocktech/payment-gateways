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

use Luminova\ExtraUtils\Payment\MerchantInterface;
use Luminova\ExtraUtils\Payment\Process;

class Payment {
    /**
    * @var MerchantInterface
    */
    private MerchantInterface $paymentMerchant;

    /**
    * @var Process static process instance  
    */
    private static $instance = null;

    /**
     * Initializes payment instance 
     * @param MerchantInterface merchant client instance 
    */
    public function __construct(MerchantInterface $merchant){
		$this->paymentMerchant = $merchant;
    }

    /**
     * Create payment instance 
     * @return Process process class instance 
    */
    public function createInstance(): Process 
    {
        $process = new Process($this->paymentMerchant->getAuthenticationKey(), $this->paymentMerchant->getBaseApi());
        return $process;
    }

    /**
     * Initialize process singleton payment instance 
     * 
     * @param MerchantInterface merchant client instance 
     * 
     * @return Process static process class instance 
    */
    public static function getInstance(MerchantInterface $merchant): Process 
    {
        if(static::$instance === null){
            static::$instance = new Process($merchant->getAuthenticationKey(), $merchant->getBaseApi());
        }
        
        return static::$instance;
    }
}