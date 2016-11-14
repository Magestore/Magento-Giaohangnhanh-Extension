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
 * Giaohangnhanh Grid Block
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('giaohangnhanhGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }
    
    /**
     * prepare collection for block to display
     *
     * @return Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('giaohangnhanh/giaohangnhanh')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    
    /**
     * prepare columns for this grid
     *
     * @return Magestore_GiaoHangNhanh_Block_Adminhtml_Giaohangnhanh_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('giaohangnhanh')->__('ID'),
            'align'     =>'right',
            'index'     => 'id',
			'type'		=> 'number',
        ));

        $this->addColumn('recipient_name', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Recipient Name'),
            'align'     =>'left',
            'index'     => 'recipient_name',
        ));

        $this->addColumn('delivery_address', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Delivery Address'),
            'index'     => 'delivery_address',
        ));

        $this->addColumn('recipient_phone', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Recipient Phone'),
            'index'     => 'recipient_phone',
        ));

        $this->addColumn('client_order_code', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Client Order Code'),
            'index'     => 'client_order_code',
        ));

       /*  $this->addColumn('cod_amount', array(
            'header'    => Mage::helper('giaohangnhanh')->__('COD Amount'),
            'width'     => '150px',
            'index'     => 'cod_amount',
        ));

        $this->addColumn('content_note', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Content Note'),
            'width'     => '150px',
            'index'     => 'content_note',
        ));

        $this->addColumn('delivery_district_code', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Delivery District Code'),
            'width'     => '150px',
            'index'     => 'delivery_district_code',
        )); */

        $this->addColumn('service_id', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Service'),
            'index'     => 'service_id',
            'type'        => 'options',
            'options'     => Mage::getModel('giaohangnhanh/status')->getOptionServiceArray(),
        ));

        $this->addColumn('pick_hub_id', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Pick Hub'),
            'index'     => 'pick_hub_id',
            'type'        => 'options',
            'options'     => Mage::getModel('giaohangnhanh/status')->getOptionPickhubArray(),
        ));

        $this->addColumn('weight', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Weight'),
            'index'     => 'weight',
			'type'		=> 'number'
        ));

       /*  $this->addColumn('length', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Length'),
            'width'     => '150px',
            'index'     => 'length',
        ));

        $this->addColumn('width', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Width'),
            'width'     => '150px',
            'index'     => 'width',
        ));

        $this->addColumn('height', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Height'),
            'width'     => '150px',
            'index'     => 'height',
        )); */

        /* $this->addColumn('estimate_fee', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Estimate Fee'),
            'width'     => '150px',
            'index'     => 'estimate_fee',
        )); */

        $this->addColumn('fee', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Fee'),
            'index'     => 'fee',
            'type'      => 'price',
            'currency_code' => $this->_getStore()->getBaseCurrency()->getCode(),
        ));
		$this->addColumn('created_time', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Created Time'),
            'index'     => 'created_time',
            'type'      => 'datetime',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('giaohangnhanh')->__('Status'),
            'align'     => 'left',
            'index'     => 'status',
            'type'        => 'options',
            'options'     => Mage::getModel('giaohangnhanh/status')->getOptionArray(),
        ));

        $this->addColumn('action',
            array(
                'header'    =>    Mage::helper('giaohangnhanh')->__('Action'),
                'type'        => 'action',
                'getter'    => 'getId',
                'actions'    => array(
                    array(
                        'caption'    => Mage::helper('giaohangnhanh')->__('Edit'),
                        'url'        => array('base'=> '*/*/edit'),
                        'field'        => 'id'
                    )),
                'filter'    => false,
                'sortable'    => false,
                'index'        => 'stores',
                'is_system'    => true,
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('giaohangnhanh')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('giaohangnhanh')->__('XML'));

        return parent::_prepareColumns();
    }
        
    /**
     * get url for each row in grid
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }
}