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
 * GiaoHangNhanh Helper
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getStoreConfig($field,$store = null)
    {
        return Mage::getStoreConfig('carriers/giaohangnhanh/'.$field, $store);
    }

	public function GetClientHubs(){
		//Get Pick Hubs
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
		$getPickHubRequest = array("SessionToken" => $sessionToken);
		$responseGetPickHubs = $api->GetClientHubs($getPickHubRequest);	
		if(!$responseGetPickHubs['ErrorMessage'] && isset($responseGetPickHubs['HubInfo']))
			return $responseGetPickHubs['HubInfo'];
		return null;
	}
	
	public function getPickHubID($pickHubID){
		$clientHubs = $this->GetClientHubs();
		foreach($clientHubs as $clientHub){
			if(isset($clientHub['DistrictCode']) && $clientHub['DistrictCode'] == $pickHubID && isset($clientHub['PickHubID']))
				return $clientHub['PickHubID'];
		}
		return 0;
	}

	public function GetDistrictProvinceData(){
		//Get District Province Data
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
		$getPickHubRequest = array("SessionToken" => $sessionToken);
		$responseGetDistrictProvinceData = $api->GetDistrictProvinceData($getPickHubRequest);	
		if(!$responseGetDistrictProvinceData['ErrorMessage'] && isset($responseGetDistrictProvinceData['Data']))
			return $responseGetDistrictProvinceData['Data'];
		return array();
	}

	public function GetServiceList($fromDistrictCode,$toDistrictCode){
		//Get Service List
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
		$getServiceList = array("SessionToken" => $sessionToken,"FromDistrictCode"=>$fromDistrictCode,"ToDistrictCode"=>$toDistrictCode);
		$responseGetServiceList = $api->GetServiceList($getServiceList);	
		if(!$responseGetServiceList['ErrorMessage'] && isset($responseGetServiceList['Services']))
			return $responseGetServiceList['Services'];
		return null;
	}
	
	public function CalculateServiceFee($fromDistrictCode,$toDistrictCode,$serviceID,$weight,$length = null,$width = null,$height = null){
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
		$store = Mage::app()->getStore()->getId();
		$item = array("FromDistrictCode"=> $fromDistrictCode,
			"ServiceID"=> $serviceID,
			"ToDistrictCode"=> $toDistrictCode,
			"Weight"=> $weight,
			"Length" => $length,
			"Width" => $width,
			"Height" => $height);		 
	    $items[] = $item;
	    $calculateServiceFeeRequest = array("SessionToken" => $sessionToken, "Items" => $items);
		$responseCalculateServiceFee = $api->CalculateServiceFee($calculateServiceFeeRequest);
		if(!$responseCalculateServiceFee['ErrorMessage'] && isset($responseCalculateServiceFee['Items']) && isset($responseCalculateServiceFee['Items'][0]) && isset($responseCalculateServiceFee['Items'][0]['ServiceFee'])){
			return Mage::app()->getStore()->convertPrice($responseCalculateServiceFee['Items'][0]['ServiceFee'],false);
		}
		return null;
	}
	
	public function CreateShippingOrder($recipientName,$deliveryAddress,$recipientPhone,$clientOrderCode,$deliveryDistrictCode,$serviceID,$pickHubID,$weight,$length = null,$width = null,$height = null,$cODAmount = null ,$contentNote = null){
		$api = Mage::getModel('giaohangnhanh/api');
		$sessionToken = $api->SignIn();
				
		$createShippingOrderRequest = array(
		"SessionToken" => $sessionToken,
		"RecipientName" => $recipientName,
		"DeliveryAddress" => $deliveryAddress,
		"RecipientPhone" => $recipientPhone,
		"ClientOrderCode" => $clientOrderCode,
		"CODAmount" => $cODAmount,
		"ContentNote" => $contentNote,
		"DeliveryDistrictCode" => $deliveryDistrictCode,
		"ServiceID" => $serviceID,
		"PickHubID" => $this->getPickHubID($pickHubID),
		"Weight" => $weight,
		"Length" => $length,
		"Width" => $width,
		"Height" => $height
		);
		
		$responseCreateShippingOrder = $api->CreateShippingOrder($createShippingOrderRequest);
		return $responseCreateShippingOrder;
	}
	
	public function GetClientHubsOption(){
		$clientHubs = $this->GetClientHubs();
		$input = '';
		
		$session = Mage::getSingleton('checkout/session');
		$pickhub = $session ->getData('pickhub','');
		
		if(0&&count($clientHubs) == 1 && isset($clientHubs[0]) && isset($clientHubs[0]['DistrictCode'])){
			$input .='<input type="hidden" value="'.$clientHubs[0]['DistrictCode'].'" name="pickhub"  id="pickhub">';
		}else if( count($clientHubs) && isset($clientHubs[0]) && isset($clientHubs[0]['DistrictCode'])){
			/* $input = '<p class="lable"><label>'.$this->__('Select Pick Hub:').'</label></p>'; */
			$input = '<div class="input-box lable">';
			$input .= '<select name="pickhub" value="'.$pickhub.'" id="pickhub" class="validate-select input-text required-entry" title="'.$this->__('Select pickhub').'">';
			$input .='<option value="">'.$this->__('Select Pick Hub').'</option>';
			foreach($clientHubs as $clientHub){
				$selected = '';
				if($pickhub == $clientHub['DistrictCode'])
					$selected="selected";
				$input .='<option '.$selected.' value="'.$clientHub['DistrictCode'].'">'.$clientHub['DistrictName'].'</option>';
			}			
			$input .= '</select>';
			$input .= '</div>';
		}else{
			$input = '<div class="input-box lable">';
			$input .= '<select name="pickhub" value="" id="pickhub" class="validate-select input-text required-entry" title="'.$this->__('There is no Pick Hub').'">';
			$input .='<option value="">'.$this->__('There is no Pick Hub').'</option>';	
			$input .= '</select>';
			$input .= '</div>';
			/* $input = '<p class="lable"><label>'.$this->__('There is no Pick Hub').'</label></p>'; */
		}
		return $input;
	}
	
	public function GetServiceListOption($pickhub,$city){
		$serviceList = $this->GetServiceList($pickhub,$city);
		$input = '';
		
		$session = Mage::getSingleton('checkout/session');
		$serviceold = $session ->getData('service','');
		if( count($serviceList) && isset($serviceList[0]) && isset($serviceList[0]['ShippingServiceID'])){
			/* $input = '<p class="lable"><label>'.$this->__('Select Service:').'</label></p>'; */
			$input = '<div class="input-box lable">';
			$input .= '<select name="service" id="service" class="validate-select input-text required-entry" title="'.$this->__('Select service').'">';
			$input .='<option value="">'.$this->__('Select Service').'</option>';
			foreach($serviceList as $service){
				$selected = '';
				if($serviceold == $service['ShippingServiceID'])
					$selected="selected";
				$input .='<option '.$selected.' value="'.$service['ShippingServiceID'].'">'.$service['Name'].'</option>';
			}			
			$input .= '</select>';
			$input .= '</div>';
		}else{
			/* $input = '<p class="lable"><label>'.$this->__('There is no service. Please check city again!').'</label></p>'; */
			$input = '<div class="input-box lable">';
			$input .= '<select name="service" id="service" class="validate-select input-text required-entry" title="'.$this->__('There is no service. Please check city again!').'">';
			$input .='<option value="">'.$this->__('There is no service. Please check city again!').'</option>';
			$input .= '</select>';
			$input .= '</div>';
		}
		return $input;
	}
	
}