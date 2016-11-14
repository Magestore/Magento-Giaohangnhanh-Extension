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
 * Giaohangnhanh Edit Block
 * 
 * @category     Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'giaohangnhanh';
        $this->_controller = 'adminhtml_giaohangnhanh';
        
        $this->_updateButton('save', 'label', Mage::helper('giaohangnhanh')->__('Save'));        
        $this->removeButton('delete');
		if(Mage::registry('giaohangnhanh_data')->getStatus() == 'ReadyToPick'){
			$this->_addButton('cancel', array(
				'label'        => Mage::helper('adminhtml')->__('Cancel'),
				'onclick'    => 'cancel()',
				'class'        => 'save',
			), -100);
			$urlCancel = $this->getUrl('*/*/cancel', array(
					'id' => $this->getRequest()->getParam('id'),
				));
			$this->_formScripts[] = "
			
				function saveAndContinueEdit(){
					editForm.submit($('edit_form').action+'back/edit/');
				}
				function cancel(){
					editForm.submit('".$urlCancel."');
				}
			";
			$this->removeButton('save');
			$this->removeButton('reset');
		}else if(Mage::registry('giaohangnhanh_data')->getStatus() != 'WaitingtoCreate'){
			$this->removeButton('save');
			$this->removeButton('reset');
		}else{
			$this->_addButton('saveandcontinue', array(
				'label'        => Mage::helper('adminhtml')->__('Save and continue'),
				'onclick'    => 'saveAndContinueEdit()',
				'class'        => 'save',
			), -100);
			
			
			$this->_addButton('saveandcreate', array(
				'label'        => Mage::helper('adminhtml')->__('Save and create order'),
				'onclick'    => 'saveAndCreate()',
				'class'        => 'save',
			), -200);

			$this->_formScripts[] = "
			
				function saveAndContinueEdit(){
					editForm.submit($('edit_form').action+'back/edit/');
				}
				
				
				function saveAndCreate(){
					editForm.submit($('edit_form').action+'back/edit/create/1/');
				}
			";
		}
    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('giaohangnhanh_data')
            && Mage::registry('giaohangnhanh_data')->getId()
        ) {
            return Mage::helper('giaohangnhanh')->__("Edit shipping '%s'",
                                                $this->htmlEscape(Mage::registry('giaohangnhanh_data')->getClientOrderCode())
            );
        }
        return Mage::helper('giaohangnhanh')->__('Add shipping');
    }
}