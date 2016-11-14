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
 * Giaohangnhanh Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getGiaoHangNhanhData()) {
            $data = Mage::getSingleton('adminhtml/session')->getGiaoHangNhanhData();
            Mage::getSingleton('adminhtml/session')->setGiaoHangNhanhData(null);
        } elseif (Mage::registry('giaohangnhanh_data')) {
            $data = Mage::registry('giaohangnhanh_data')->getData();
        }
		$disable = false;
		if(isset($data['status']) && $data['status'] != 'WaitingtoCreate'){
			$disable = true;
		}
        $fieldset = $form->addFieldset('giaohangnhanh_form', array(
            'legend'=>Mage::helper('giaohangnhanh')->__('Shipping information')
        ));

        $fieldset->addField('recipient_name', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Recipient Name'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'recipient_name',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('delivery_address', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Delivery Address'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'delivery_address',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('recipient_phone', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Recipient Phone'),
            'class'        => 'required-entry',
            'required'    => true, 
            'name'        => 'recipient_phone',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('client_order_code', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Client Order Code'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'client_order_code',
			'disabled'		=> $disable,
        ));
		if($data['order_code'])
        $fieldset->addField('order_code', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Order Code'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'order_code',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('cod_amount', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('COD Amount'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'cod_amount',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('content_note', 'textarea', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Content Note'),
            'name'        => 'content_note',
            'style'        => 'width:275px; height:100px;',
            'wysiwyg'    => false,
			'disabled'		=> $disable,
        ));

        $fieldset->addField('delivery_district_code', 'select', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Delivery District'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'delivery_district_code',
            'values'    => Mage::getSingleton('giaohangnhanh/status')->getOptionDistrictHash(),
			'disabled'		=> $disable,
        ));

        $fieldset->addField('service_id', 'select', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Service'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'service_id',
            'values'    => Mage::getSingleton('giaohangnhanh/status')->getOptionServiceHash(),
			'disabled'		=> $disable,
        ));

        $fieldset->addField('pick_hub_id', 'select', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Pick Hub'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'pick_hub_id',
            'values'    => Mage::getSingleton('giaohangnhanh/status')->getOptionPickhubHash(),
			'disabled'		=> $disable,
        ));

        $fieldset->addField('weight', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Weight'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'weight',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('length', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Length'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'length',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('width', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Width'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'width',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('height', 'text', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Height'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'height',
			'disabled'		=> $disable,
        ));

        $fieldset->addField('estimate_fee', 'note', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Estimated Fee'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'estimate_fee',
			'text'		  => Mage::app()->getStore()->convertPrice($data['estimate_fee'],true),
			'disabled'		=> $disable,
        ));

        $fieldset->addField('fee', 'note', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Fee'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'fee',
			'text'		  => Mage::app()->getStore()->convertPrice($data['fee'],true),
			'disabled'		=> $disable,
        ));
		
		if(isset($data['expected_delivery_time'])){
			$date = $data['expected_delivery_time'];
			preg_match('/(\d{10})(\d{3})/', $date, $matches);
			if(isset($matches['1']))
			$fieldset->addField('expected_delivery_time', 'note', array(
				'label'        => Mage::helper('giaohangnhanh')->__('Expected Delivery Time'),
				'class'        => 'required-entry',
				'required'    => true,
				'name'        => 'expected_delivery_time',
				'text'		  => date('d-m-Y H:i:s',$matches['1']),
				'disabled'		=> $disable,
			));
		}
		
		$created_time = $this->formatTime($data['created_time'],Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM,true);
        $fieldset->addField('created_time_show', 'note', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Created Time'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'created_time_show',
			'text'			=>$created_time,
			'disabled'		=> $disable,
        ));
		$update_time = $this->formatTime($data['update_time'],Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM,true);
        $fieldset->addField('update_time_show', 'note', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Updated Time'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'update_time_show',
			'text'			=>$update_time,
			'disabled'		=> $disable,
        ));
		$data['status_show'] = $data['status'];
        $fieldset->addField('status_show', 'select', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Status'),
            'name'        => 'status_show',
            'values'    => Mage::getSingleton('giaohangnhanh/status')->getOptionHash(),
			'disabled'		=> true,
        ));
		
		if(isset($data['return_info'])){
			$fieldset->addField('return_info', 'note', array(
				'label'        => Mage::helper('giaohangnhanh')->__('Return Information'),
				'class'        => 'required-entry',
				'required'    => true,
				'name'        => 'return_info',
				'text'		  => $data['return_info'],
				'disabled'		=> $disable,
			));
		}		
		
		if(isset($data['start_return_time'])){
			$fieldset->addField('start_return_time', 'note', array(
				'label'        => Mage::helper('giaohangnhanh')->__('Start Return Time'),
				'class'        => 'required-entry',
				'required'    => true,
				'name'        => 'start_return_time',
				'text'		  => $data['start_return_time'],
				'disabled'		=> $disable,
			));
		}
		
		$fieldset->addField('status', 'hidden', array(
            'label'        => Mage::helper('giaohangnhanh')->__('Status'),
            'name'        => 'status',
		));

		$fieldset->addField('created_time', 'hidden', array(
            'label'        => Mage::helper('giaohangnhanh')->__('created_time'),
            'name'        => 'created_time',
		));

		$fieldset->addField('update_time', 'hidden', array(
            'label'        => Mage::helper('giaohangnhanh')->__('update_time'),
            'name'        => 'update_time',
		));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}