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
 * Giaohangnhanh Model
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Model_Giaohangnhanh extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('giaohangnhanh/giaohangnhanh');
    }
	
	public function createShippingOrder(){
		if($this->getStatus() != 'WaitingtoCreate')
			return $this;
		$array = Mage::helper('giaohangnhanh')->CreateShippingOrder($this->getRecipientName(),$this->getDeliveryAddress(),$this->getRecipientPhone(),$this->getClientOrderCode(),$this->getDeliveryDistrictCode(),$this->getServiceId(),$this->getPickHubId(),$this->getWeight(),$this->getLength(),$this->getWidth(),$this->getHeight(),$this->getCodAmount(),$this->getContentNote());
		
		if($array['ErrorMessage']){
			Mage::getSingleton('adminhtml/session')->addError(
				Mage::helper('giaohangnhanh')->__('Error: %s',$array['ErrorMessage'])
            );
		}else if(isset($array['TotalFee'])){
			try{
				$this->setFee($array['TotalFee'])->setOrderCode($array['OrderCode'])->setStatus('ReadyToPick')->setUpdateTime(now())->save();
				Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('giaohangnhanh')->__('Shipping Order %s was successfully created',$this->getClientOrderCode())
                );
			 } catch (Exception $e) {}
		}
		
		return $this;
	}
	
	public function cancelShippingOrder(){
		if($this->getStatus() != 'ReadyToPick')
			return array(
				'ErrorMessage' =>  Mage::helper('giaohangnhanh')->__('Cannot cancel the %s Shipping Order!',$this->getStatus())
			);
			
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
		$cancelOrderRequest = array("SessionToken" => $sessionToken, "OrderCode" => $this->getOrderCode());
		$responseCancelShippingOrder = $api->CancelOrder($cancelOrderRequest);
		if(!$responseCancelShippingOrder['ErrorMessage']){
			try{
				$this->setStatus('Cancel')->setUpdateTime(now())->save();
			} catch (Exception $e) {}
		}
			
		return $responseCancelShippingOrder;
	}
	
	public function getOrderInfo(){
		$arr = array('Finish','WaitingtoCreate','Return','Cancel');
		if(!$this->getOrderCode() || in_array($this->getStatus(),$arr))
			return array();
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
		$orderInfoRequest = array("SessionToken" => $sessionToken,'OrderCode'=>$this->getOrderCode());
		$responseGetOrderInfo = $api->GetOrderInfo($orderInfoRequest);
		if(!$responseGetOrderInfo['ErrorMessage']){
			try{
				$this->setStatus($responseGetOrderInfo['CurrentStatus'])
					->setWeight($responseGetOrderInfo['Weight'])
					->setLength($responseGetOrderInfo['Length'])
					->setWidth($responseGetOrderInfo['Width'])
					->setHeight($responseGetOrderInfo['Height'])
					->setFee($responseGetOrderInfo['TotalServiceCost'])
					->setUpdateTime(now())
					->save();
			} catch (Exception $e) {}
		}	
		return $responseGetOrderInfo;
	}
	
	
}