<?php
class Magestore_GiaoHangNhanh_Model_Carrier_Feeshipping
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'giaohangnhanh';

   
	public function getCode()
	{
		return $this->_code;
	}
	
	public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {		
		if (!$this->getConfigFlag('active')) 
		{
            return false;
        }
		
		$items = $request->getAllItems();
		
		if(! count($items))
			return;
			
		$session = Mage::getSingleton('checkout/session');
		$shipping_fee = $session ->getData('shipping_fee');
		$store = Mage::app()->getStore()->getId();
		if(!$shipping_fee)
			$shipping_fee = Mage::helper('giaohangnhanh')->getStoreConfig('shipping_fee',$store);
		$result = Mage::getModel('shipping/rate_result');
		
		$method = Mage::getModel('shipping/rate_result_method');

		$method->setCarrier('giaohangnhanh');
					
		$method->setCarrierTitle($this->getConfigData('title'));
			
		$method->setMethod('giaohangnhanh');
					
		$method->setMethodTitle(Mage::helper('giaohangnhanh')->__('Fee'));
				
		$method->setPrice($shipping_fee);
		
		$method->setCost($shipping_fee);		
				
		$result->append($method);	
		
		return $result;		
    }

    public function getAllowedMethods()
    {
        return array('giaohangnhanh'=>'giaohangnhanh');
    }
}

