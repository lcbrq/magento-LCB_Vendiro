<?php

/**
 * Basic Vendiro integration
 *
 * @category   LCB
 * @package    LCB_Vendiro
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Vendiro_Model_Stock {

    protected static $endpoint;

    /**
     * Send store stock to Vendiro
     */
    public function send($fromShell = false)
    {
        $username = Mage::getStoreConfig('vendiro/general/username', Mage::app()->getStore());
        self::$endpoint = 'https://api-test.vendiro.nl/stock/' . $username . '/';
        $collection = Mage::getResourceModel('catalog/product_collection');
        self::process($collection, $fromShell);
    }

    /**
     * Process mass send in bulk
     */
    public function process($collection, $fromShell)
    {
        $collection->setPageSize(100);

        $pages = $collection->getLastPageNumber();
        $page = 1;

        do {

            $request = "";
            $collection->setCurPage($page);
            $collection->load();

            foreach ($collection as $_product) {
                $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($_product->getId());
                $request .= "sku=" . $_product->getSku() . "&qty=" . $stock->getQty() . "\n";
            }

            $remote = curl_init();
            curl_setopt($remote, CURLOPT_URL, self::$endpoint);
            curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($remote, CURLOPT_POST, 1);
            curl_setopt($remote, CURLOPT_POSTFIELDS, $request);

            if ($fromShell) {
                echo "$request\n";
            }

            $response = curl_exec($remote);
            $info = curl_getinfo($remote);
            $response = curl_close($remote);

            if ($fromShell) {
                var_export($response) . "\n";
            }

            $page++;
            $collection->clear();
        } while ($page <= $pages);
    }

}
