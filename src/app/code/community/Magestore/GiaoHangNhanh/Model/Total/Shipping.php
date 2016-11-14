<?php

class Magestore_GiaoHangNhanh_Model_Total_Shipping extends Mage_Sales_Model_Quote_Address_Total_Abstract {

    public function collect(Mage_Sales_Model_Quote_Address $address) {
        $session = Mage::getSingleton('checkout/session');
		
        $shippingMethod = $address->getShippingMethod();
        $shippingMethod = explode('_', $shippingMethod);
        $shippingCode = $shippingMethod[0];
        if ($shippingCode != "giaohangnhanh")
            return $this;
		$serviceArray = Mage::getModel('giaohangnhanh/status')->getOptionServiceArray();
		$pickhubArray = Mage::getModel('giaohangnhanh/status')->getOptionPickhubArray();
		$cityArray = Mage::getModel('giaohangnhanh/status')->getOptionDistrictArray();
		
		$city = $session ->getData('city');
		$pickhub = $session ->getData('pickhub');
		$service = $session ->getData('service');
		
		$address->setDelivery($city);
		$address->setPickhub($pickhub);
		$address->setService($service);
		
		if(isset($cityArray[$city]) && $cityArray[$city])
			$city = $cityArray[$city];
		if(isset($pickhubArray[$pickhub]) && $pickhubArray[$pickhub])
			$pickhub = $pickhubArray[$pickhub];
		if(isset($serviceArray[$service])&& $serviceArray[$service])
			$service = $serviceArray[$service];
        if ($city && $pickhub && $service)
            $address->setShippingDescription(Mage::helper('giaohangnhanh')->__(' Từ: %s,',$pickhub). Mage::helper('giaohangnhanh')->__(' Đến: %s,',$city). Mage::helper('giaohangnhanh')->__(' Dịch vụ: %s - ',$service). Mage::helper('giaohangnhanh')->getStoreConfig('title',Mage::app()->getStore()->getId()));
        return $this;
    }

}
