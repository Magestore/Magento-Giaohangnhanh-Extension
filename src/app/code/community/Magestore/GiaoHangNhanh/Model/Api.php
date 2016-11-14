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
class Magestore_GiaoHangNhanh_Model_Api
{
	const METHODE_GET = 'GET';
	const METHODE_POST = 'POST';

	protected $validMethods = array(
		self::METHODE_GET,
		self::METHODE_POST,
	);
	protected $apiUrl;
	protected $cURL;
	protected $clientID;
	protected $apiKey;
	protected $apiSecretKey;
	protected $password;

	public function __construct()
	{
		$store = Mage::app()->getStore()->getId();
		$this->apiUrl = rtrim(Mage::helper('giaohangnhanh')->getStoreConfig('api_url',$store), '/') . '/';
		$this->clientID = Mage::helper('giaohangnhanh')->getStoreConfig('client_id',$store);
		$this->password = Mage::helper('giaohangnhanh')->getStoreConfig('password',$store);
		$this->apiKey = Mage::helper('giaohangnhanh')->getStoreConfig('api_key',$store);
		$this->apiSecretKey = Mage::helper('giaohangnhanh')->getStoreConfig('api_secret_key',$store);
			
		$this->cURL = curl_init();
		curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, false);
		curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->cURL, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
	}

	public function call($url, $method = self::METHODE_GET, $data = array(), $params = array())
	{
		if (!in_array($method, $this->validMethods)) {
			throw new Exception('Invalid HTTP-Methods: ' . $method);
		}
		$queryString = '';
		if (!empty($params)) {
			$queryString = http_build_query($params);
		}
		$url = rtrim($url, '?') . '?';
		$url = $this->apiUrl . $url . $queryString;
		$jsonData = json_encode($data);
		
		curl_setopt($this->cURL, CURLOPT_URL, $url);
		curl_setopt($this->cURL, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $jsonData);
		$result = curl_exec($this->cURL);
		$httpCode = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);

		return $this->response($result, $httpCode);
	}

	private function get($url, $params = array())
	{
		return $this->call($url, self::METHODE_GET, array(), $params);
	}

	private function post($url, $data = array(), $params = array())
	{
		return $this->call($url, self::METHODE_POST, $data, $params);
	}
	
	public function SignIn()
	{
		$i = 1;
		do{
			$result = $this->post('SignIn', array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password));
			$i++;
		}while($result['ErrorMessage'] && $i < 3);
		if (empty($result['ErrorMessage'])) {
			return $result['SessionToken'];
		}
		return null;
	}

	public function SignOut()
	{
		return $this->post('SignOut', array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"SessionToken" => $this->sessionClient));
	}

	protected function response($result, $httpCode)
	{
		//var_export($result); // Print Log
		if (null === $decodedResult = json_decode($result, true))
		{
			//echo "Could not decode json. " . print_r($result, true);
			return;
		}
		if ($decodedResult['ErrorMessage'] != null)
		{
			$this->errorMessager = $decodedResult['ErrorMessage'];
		}
		return $decodedResult;
	}
	
	public function CreateShippingOrder($orderRequest)
	{
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $orderRequest);
		
		return $this->post('CreateShippingOrder', $request);
	}
		
	public function GetClientHubs($getPickHubRequest)
	{
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $getPickHubRequest);
		
		return $this->post('GetPickHubs', $request);
	}
	
	public function GetServiceList($getServiceList){
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $getServiceList);
		
		return $this->post('GetServiceList', $request);
	}
		
	public function CancelOrder($cancelOrderRequest)
	{
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $cancelOrderRequest);
			
		return $this->post('CancelOrder', $request);
	}
		
	public function GetDistrictProvinceData($districtProvinceDataRequest)
	{
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $districtProvinceDataRequest);
			
		return $this->post('GetDistrictProvinceData',$request);
	}
		
	public function CalculateServiceFee($calculateServiceFeeRequest)
	{
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $calculateServiceFeeRequest);
			
		return $this->post('CalculateServiceFee',$request);
	}
	
		
	public function GetOrderInfo($orderInfoRequest)
	{
		$header = array("ApiKey" => $this->apiKey, "ApiSecretKey" => $this->apiSecretKey,"ClientID" => $this->clientID, "Password" => $this->password);
		$request = array_merge($header, $orderInfoRequest);
			
		return $this->post('GetOrderInfo',$request);
	}
	
}