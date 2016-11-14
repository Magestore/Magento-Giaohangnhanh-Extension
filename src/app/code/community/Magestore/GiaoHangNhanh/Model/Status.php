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
 * GiaoHangNhanh Status Model
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Model_Status extends Varien_Object
{    
	protected $_optionPickhubArray;
	protected $_optionDistrictArray;
	
	public function _construct()
    {
        parent::_construct();
    }
    /**
     * get model option as array
     *
     * @return array
     */
    public function getOptionArray()
    {
        return array(
            "WaitingtoCreate"    => Mage::helper('giaohangnhanh')->__('Waiting to Create'),
            "ReadyToPick"    => Mage::helper('giaohangnhanh')->__('Ready To Pick'),
            "Picking"   => Mage::helper('giaohangnhanh')->__('Picking'),
            "Storing"    => Mage::helper('giaohangnhanh')->__('Storing'),
            "Delivering"   => Mage::helper('giaohangnhanh')->__('Delivering'),
            "Delivered"    => Mage::helper('giaohangnhanh')->__('Delivered'),
            "WaitingtoCreateingToFinish"   => Mage::helper('giaohangnhanh')->__('waiting for finishing'),
            "Finish"    => Mage::helper('giaohangnhanh')->__('Finish'),
            "Return"   => Mage::helper('giaohangnhanh')->__('Return'),
            "Cancel"   => Mage::helper('giaohangnhanh')->__('Cancel'),
        );
    }
    
    /**
     * get model option hash as array
     *
     * @return array
     */
    public function getOptionHash()
    {
        $options = array();
        foreach (self::getOptionArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }
	
	/**
     * get model option service as array
     *
     * @return array
     */
    public function getOptionServiceArray()
    {
        return array(
            "53319"    => Mage::helper('giaohangnhanh')->__('6 hours'),
            "53320"    => Mage::helper('giaohangnhanh')->__('one day'),
            "53321"   => Mage::helper('giaohangnhanh')->__('2 days'),
            "53322"    => Mage::helper('giaohangnhanh')->__('3 days'),
            "53323"   => Mage::helper('giaohangnhanh')->__('4 days'),
            "53324"    => Mage::helper('giaohangnhanh')->__('5 days'),
            "53327"    => Mage::helper('giaohangnhanh')->__('6 days'),
            "53325"   => Mage::helper('giaohangnhanh')->__('Prime')
        );
    }
    
    /**
     * get model option service hash as array
     *
     * @return array
     */
    public function getOptionServiceHash()
    {
        $options = array();
        foreach (self::getOptionServiceArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }
	
	/**
     * get model option pickhub as array
     *
     * @return array
     */
    public function getOptionPickhubArray()
    {
		if (is_null($this->_optionPickhubArray)) {
			$clientHubs = Mage::helper('giaohangnhanh')->GetClientHubs();
			$array = array();
			if(count($clientHubs))
			foreach($clientHubs as $clientHub){
				$array[$clientHub['DistrictCode']] = $clientHub['DistrictName'];
			}
			$this->_optionPickhubArray = $array;
		}
        return $this->_optionPickhubArray;
    }
    
    /**
     * get model option service hash as array
     *
     * @return array
     */
    public function getOptionPickhubHash()
    {
        $options = array();
        foreach (self::getOptionPickhubArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }
	
	public function getDistrictCodeByName($name){
		/* if($index = array_search($name,$this->getOptionDistrictArray())) */
		if($index = array_search(strtolower($name), array_map('strtolower', $this->getOptionDistrictArray())))
			return $index;
		return false;
	}
	
	/**
     * get model option District as array
     *
     * @return array
     */
    public function getOptionDistrictArray()
    {
		if (is_null($this->_optionDistrictArray)) {
			$districtProvinceData = Mage::helper('giaohangnhanh')->GetDistrictProvinceData();
			$array = array();
			if(count($districtProvinceData))
			foreach($districtProvinceData as $districtProvince){
				$array[$districtProvince['DistrictCode']] = $districtProvince['DistrictName'];
			}
			$this->_optionDistrictArray = $array;
		}
        return $this->_optionDistrictArray;
    }
    
    /**
     * get model option service hash as array
     *
     * @return array
     */
    public function getOptionDistrictHash()
    {
        $options = array();
        foreach (self::getOptionDistrictArray() as $value => $label) {
            $options[] = array(
                'value'    => $value,
                'label'    => $label
            );
        }
        return $options;
    }
	
}