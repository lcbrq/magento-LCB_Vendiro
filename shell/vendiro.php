<?php

/**
 * Basic Vendiro integration
 *
 * @category   LCB
 * @package    LCB_Vendiro
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
require_once 'abstract.php';

class Vendiro_Shell extends Mage_Shell_Abstract {

    /**
     * Send stock to Vendiro.nl gateway
     */
    public function run()
    {
        Mage::getModel('vendiro/stock')->send(true);
    }

}

$shell = new Vendiro_Shell();
$shell->run();
