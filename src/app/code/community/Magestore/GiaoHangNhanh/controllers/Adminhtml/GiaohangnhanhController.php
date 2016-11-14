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
 * Giaohangnhanh Adminhtml Controller
 * 
 * @category    Magestore
 * @package     Magestore_GiaoHangNhanh
 * @author      Magestore Developer
 */
class Magestore_GiaoHangNhanh_Adminhtml_GiaohangnhanhController extends Mage_Adminhtml_Controller_Action
{
    /**
     * init layout and set active for current menu
     *
     * @return Magestore_GiaoHangNhanh_Adminhtml_GiaohangnhanhController
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('giaohangnhanh/giaohangnhanh')
            ->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Shipping Manager'),
                Mage::helper('adminhtml')->__('Shipping Manager')
            );
        return $this;
    }
 
    /**
     * index action
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * view and edit item action
     */
    public function editAction()
    {
        $giaohangnhanhId     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('giaohangnhanh/giaohangnhanh')->load($giaohangnhanhId);
		$data = $model->getOrderInfo();
		if(isset($data['ReturnInfo'])){
			$model->setReturnInfo($data['ReturnInfo']);
		}
		if(isset($data['ExpectedDeliveryTime'])){
			$model->setExpectedDeliveryTime($data['ExpectedDeliveryTime']);
		}
		if(isset($data['StartReturnTime'])){
			$model->setStartReturnTime($data['StartReturnTime']);
		}
        if ($model->getId() || $giaohangnhanhId == 0) {
            $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
            if (!empty($data)) {
                $model->setData($data);
            }
            Mage::register('giaohangnhanh_data', $model);

            $this->loadLayout();
            $this->_setActiveMenu('giaohangnhanh/giaohangnhanh');

            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Shipping Manager'),
                Mage::helper('adminhtml')->__('Shipping Manager')
            );
            $this->_addBreadcrumb(
                Mage::helper('adminhtml')->__('Edit shipping'),
                Mage::helper('adminhtml')->__('Edit shipping')
            );

            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('giaohangnhanh/adminhtml_giaohangnhanh_edit'))
                ->_addLeft($this->getLayout()->createBlock('giaohangnhanh/adminhtml_giaohangnhanh_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('giaohangnhanh')->__('The shipping does not exist!')
            );
            $this->_redirect('*/*/');
        }
    }
 
    public function newAction()
    {
        $this->_forward('edit');
    }
 
    /**
     * save item action
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
              
            $model = Mage::getModel('giaohangnhanh/giaohangnhanh');        
            $model->setData($data)
                ->setId($this->getRequest()->getParam('id'));
            
            try {
				if ($model->getCreatedTime() == NULL || $model->getUpdateTime() == NULL) {
                    $model->setCreatedTime(now())
                        ->setUpdateTime(now());
                } else {
                    $model->setUpdateTime(now());
                }
                $model->save();
                
                Mage::getSingleton('adminhtml/session')->setFormData(false);
				if ($this->getRequest()->getParam('create') && $model->getStatus() == 'WaitingtoCreate' ) {
					$model->createShippingOrder();
                }else{
					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('giaohangnhanh')->__('The shipping was successfully saved.')
					);
				}
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('giaohangnhanh')->__('Unable to find shiping to save.')
        );
        $this->_redirect('*/*/');
    }
	
	
	public function cancelAction(){
		if ($id = $this->getRequest()->getParam('id')) {
			try {
				$model = Mage::getModel('giaohangnhanh/giaohangnhanh')->load($id);
				$responseCancelShippingOrder = $model ->cancelShippingOrder();
				if($responseCancelShippingOrder['ErrorMessage']){
					Mage::getSingleton('adminhtml/session')->addError(
						Mage::helper('giaohangnhanh')->__('Error: %s',$responseCancelShippingOrder['ErrorMessage'])
					);
				}else{
					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('giaohangnhanh')->__('The shipping was successfully canceled.')
					);
				}
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
			} catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
		}
		
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('giaohangnhanh')->__('The shipping does not exist!')
        );
        $this->_redirect('*/*/');
	}
 
    /**
     * delete item action
     */
    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('giaohangnhanh/giaohangnhanh');
                $model->setId($this->getRequest()->getParam('id'))
                    ->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('The shipping was successfully deleted.')
                );
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    /**
     * mass delete item(s) action
     */
    public function massDeleteAction()
    {
        $giaohangnhanhIds = $this->getRequest()->getParam('giaohangnhanh');
        if (!is_array($giaohangnhanhIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select shipping(s).'));
        } else {
            try {
                foreach ($giaohangnhanhIds as $giaohangnhanhId) {
                    $giaohangnhanh = Mage::getModel('giaohangnhanh/giaohangnhanh')->load($giaohangnhanhId);
                    $giaohangnhanh->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('adminhtml')->__('Total of %d shipping(s) were successfully deleted.',
                    count($giaohangnhanhIds))
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * mass change status for item(s) action
     */
    public function massStatusAction()
    {
        $giaohangnhanhIds = $this->getRequest()->getParam('giaohangnhanh');
        if (!is_array($giaohangnhanhIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select shipping(s).'));
        } else {
            try {
                foreach ($giaohangnhanhIds as $giaohangnhanhId) {
                    Mage::getSingleton('giaohangnhanh/giaohangnhanh')
                        ->load($giaohangnhanhId)
                        ->setStatus($this->getRequest()->getParam('status'))
                        ->setIsMassupdate(true)
                        ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d record(s) were successfully updated', count($giaohangnhanhIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export grid item to CSV type
     */
    public function exportCsvAction()
    {
        $fileName   = 'giaohangnhanh.csv';
        $content    = $this->getLayout()
                           ->createBlock('giaohangnhanh/adminhtml_giaohangnhanh_grid')
                           ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export grid item to XML type
     */
    public function exportXmlAction()
    {
        $fileName   = 'giaohangnhanh.xml';
        $content    = $this->getLayout()
                           ->createBlock('giaohangnhanh/adminhtml_giaohangnhanh_grid')
                           ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('giaohangnhanh');
    }
}