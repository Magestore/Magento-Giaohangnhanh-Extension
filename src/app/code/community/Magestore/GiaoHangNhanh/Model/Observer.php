<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * GiaoHangNhanh Observer Model
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Model_Observer
{
    /**
     * process controller_action_predispatch event
     *
     * @return Magestore_GiaoHangNhanh_Model_Observer
     */
    public function salesOrderSaveAfter($observer)
    {
		$session = Mage::getSingleton('checkout/session');
		$session ->unsetData('city');
		$session ->unsetData('pickhub');
		$session ->unsetData('service');
		$session ->unsetData('shipping_fee');
		$order = $observer['order'];
		if(is_object($order) && $order->getShippingMethod() != 'giaohangnhanh_giaohangnhanh')
			return $this;
		$shippingOrder = Mage::getModel('giaohangnhanh/giaohangnhanh')->load($order->getIncrementId(),'client_order_code');
		if(!$shippingOrder->getId()){
			$shippingAdress = $order->getShippingAddress();
			$street = $shippingAdress->getStreet();
			if(is_array($street) && isset($street[0]))
			$street = $street[0];
			$cityArray = Mage::getModel('giaohangnhanh/status')->getOptionDistrictArray();
			$city = $shippingAdress->getCity();
			if(isset($cityArray[$city]) && $cityArray[$city])
			$city = $cityArray[$city];
			$deliveryAddress =  $street.', '.$city.', '.$shippingAdress->getRegion().', '.$shippingAdress->getCountryId();
			$shippingOrder->setRecipientName($shippingAdress->getFirstname().' '.$shippingAdress->getLastname())
						->setDeliveryAddress($deliveryAddress)
						->setRecipientPhone($shippingAdress->getTelephone())
						->setClientOrderCode($order->getIncrementId())
						/* ->setCodAmount(0)
						->setContentNote(0) */
						->setDeliveryDistrictCode($order->getDelivery())
						->setServiceId($order->getService())
						->setPickHubId($order->getPickhub())
						->setWeight($order->getWeight())
						->setEstimateFee($order->getShippingAmount())
						->setCreatedTime(now())
						->setUpdateTime(now())
						->setStatus('WaitingtoCreate')
						;
			 try {
				$shippingOrder->save();
			 } catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('core/session')->addError($e->getMessage());
			 }
		}
        return $this;
    }
	
	public function updateOrderInfo(){
		$arr = array('Finish','WaitingtoCreate','Return','Cancel');
		$orders = Mage::getModel('giaohangnhanh/giaohangnhanh')
				->getCollection()
				->addFieldToFilter('status',array('nin'=>$arr));
		if(count($orders))
			foreach($orders as $model){
				$model->getOrderInfo();
			}
		return $this;
	}
}