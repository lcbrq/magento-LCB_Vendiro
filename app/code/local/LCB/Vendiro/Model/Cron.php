<?php

/**
 * Basic Vendiro integration
 *
 * @category   LCB
 * @package    LCB_Vendiro
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Vendiro_Model_Cron {

    /**
     * Send Vendiro stock by CRON
     * 
     * @return void
     */
    public function sendStock()
    {
        if (Mage::getStoreConfig('vendiro/general/enabled', Mage::app()->getStore())) {
            Mage::getModel('vendiro/stock')->send();
        }
    }

}
