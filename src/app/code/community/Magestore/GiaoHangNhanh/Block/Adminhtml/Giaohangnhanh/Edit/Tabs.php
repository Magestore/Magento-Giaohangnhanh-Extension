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
 * Giaohangnhanh Edit Tabs Block
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('giaohangnhanh_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('giaohangnhanh')->__('Shipping information'));
    }
    
    /**
     * prepare before render block to html
     *
     * @return Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Edit_Tabs
     */
    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label'     => Mage::helper('giaohangnhanh')->__('Shipping information'),
            'title'     => Mage::helper('giaohangnhanh')->__('Shipping information'),
            'content'   => $this->getLayout()
                                ->createBlock('giaohangnhanh/adminhtml_giaohangnhanh_edit_tab_form')
                                ->toHtml(),
        ));
        return parent::_beforeToHtml();
    }
}