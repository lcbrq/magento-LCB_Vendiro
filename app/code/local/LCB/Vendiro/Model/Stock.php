<?php

/**
 * Basic Vendiro integration
 *
 * @category   LCB
 * @package    LCB_Vendiro
 * @author     Silpion Tomasz Gregorczyk <tom@leftcurlybracket.com>
 */
class LCB_Vendiro_Model_Stock extends LCB_Vendiro_Model_Api {

    /**
     * Send store stock to Vendiro
     */
    public function send()
    {
        self::$endpoint .= '/stock/' . self::$username . '/';
        $collection = Mage::getResourceModel('catalog/product_collection');
        self::process($collection);
    }

    /**
     * Process mass send in bulk
     */
    public function process($collection)
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
                if ($stock->getManageStock() == 1 || $stock->getUseConfigManageStock() == 1) {
                    $request .= "sku=" . $_product->getSku() . "&qty=" . $stock->getQty() . "\n";
                }
            }
            
            $remote = curl_init();
            curl_setopt($remote, CURLOPT_URL, self::$endpoint);
            curl_setopt($remote, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($remote, CURLOPT_POST, 1);
            curl_setopt($remote, CURLOPT_POSTFIELDS, $request);

            if (self::$echo) {
                echo "$request\n";
            }

            $response = curl_exec($remote);
            $info = curl_getinfo($remote);
            $response = curl_close($remote);

            if (self::$echo) {
                var_export($response) . "\n";
            }

            $page++;
            $collection->clear();
        } while ($page <= $pages);
    }

}
