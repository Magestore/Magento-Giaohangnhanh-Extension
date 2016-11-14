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
 * GiaoHangNhanh Index Controller
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * index action
     */
    public function indexAction()
    {
		
	$cityArray = Mage::getModel('giaohangnhanh/observer')->updateOrderInfo();
	/* var_dump( array_search('Quận 1',$cityArray,true)); */
//Zend_debug::dump($cityArray);
		die();
        $this->loadLayout();
        $this->renderLayout();
    }
	
	/**
     * changePickHub action
     */
    public function changePickHubAction()
    {	 
		$result = array();
		$city = $this->getRequest()->getParam('city',null);
		$pickhub = $this->getRequest()->getParam('pickhub',null);
		$quote = $this->getQuote();
		
		$status = Mage::getModel('giaohangnhanh/status');
		if($index = $status->getDistrictCodeByName($city)){
			$city = $index;
		}else if(is_object($quote) && is_object($quote->getShippingAddress()) && $quote->getShippingAddress()->getCity() && $index = $status->getDistrictCodeByName($quote->getShippingAddress()->getCity())){
			$city = $index;
		}
		
		$session = Mage::getSingleton('checkout/session');
		$session ->setData('city',$city);
		$session ->setData('pickhub',$pickhub);
		$input = '<br/>';
		if($city && $pickhub){
			$input = Mage::helper('giaohangnhanh')->GetServiceListOption($pickhub,$city);
		}elseif($city){
			//$input = '<p class="label"><label>'.$this->__('Please choose a Pick Hub!').'</label></p>';
		}else{
			//$input = '<p class="label"><label>'.$this->__('Please choose the city!').'</label></p>';
		}
		
		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode($input));
	}
	
	/**
     * changeService action
     */
    public function changeServiceAction()
    {	 
		$result = array();
		$city = $this->getRequest()->getParam('city',null);
		$pickhub = $this->getRequest()->getParam('pickhub',null);
		$service = $this->getRequest()->getParam('service',null);
		$quote = $this->getQuote();
		$weight = 0;
		if(is_object($quote) && is_object($quote->getShippingAddress()) && $quote->getShippingAddress()->getWeight()){
			$weight = $quote->getShippingAddress()->getWeight();
		}
		
		$status = Mage::getModel('giaohangnhanh/status');
		if($index = $status->getDistrictCodeByName($city)){
			$city = $index;
		}else if(is_object($quote) && is_object($quote->getShippingAddress()) && $quote->getShippingAddress()->getCity() && $index = $status->getDistrictCodeByName($quote->getShippingAddress()->getCity())){
			$city = $index;
		}
		
		$session = Mage::getSingleton('checkout/session');
		$session ->setData('city',$city);
		$session ->setData('pickhub',$pickhub);
		$session ->setData('service',$service);
		
		if($city && $pickhub && $service && $weight){
			$input = Mage::helper('giaohangnhanh')->CalculateServiceFee($pickhub,$city,$service,$weight);
		}else{
			$store = Mage::app()->getStore()->getId();
			$shipping_fee = Mage::helper('giaohangnhanh')->getStoreConfig('shipping_fee',$store);
			$session ->setData('shipping_fee',0);
			$input = Mage::app()->getStore()->convertPrice($shipping_fee,false);
		}
		
		$session ->setData('shipping_fee',$input);
		Mage::getSingleton('checkout/session')->getQuote()->setFlag(false)->getShippingAddress()->collectShippingRates()->save();
		Mage::getSingleton('checkout/session')->getQuote()->setFlag(false)->collectTotals()->save();

		$this->getResponse()->setBody(Mage::helper('core')->jsonEncode(Mage::app()->getStore()->convertPrice($input,true)));
	}
	
	/**
     * get current checkout quote
     * 
     * @return Mage_Sales_Model_Quote
     */
    public function getQuote() {
        if (Mage::app()->getStore()->isAdmin()) {
            return Mage::getSingleton('adminhtml/session_quote')->getQuote();
        }
        return Mage::getSingleton('checkout/session')->getQuote();
    }
}