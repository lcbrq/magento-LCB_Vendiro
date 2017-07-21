<?php

/**
 * Basic Vendiro integration
 *
 * @category   LCB
 * @package    LCB_Vendiro
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
abstract class LCB_Vendiro_Model_Api {

    protected static $endpoint;
    protected static $username;
    public static $echo;

    public function __construct($fromShell)
    {
        if(Mage::getStoreConfig('vendiro/general/sandbox', Mage::app()->getStore())){
            self::$endpoint = 'https://api-test.vendiro.nl';
        } else {
            self::$endpoint = 'https://api.vendiro.nl';
        }
        self::$username = Mage::getStoreConfig('vendiro/general/username', Mage::app()->getStore());
        self::$echo = $fromShell;
    }
    
}
