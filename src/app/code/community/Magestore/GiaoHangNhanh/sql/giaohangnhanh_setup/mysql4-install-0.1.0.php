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

/** @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()->addColumn($this->getTable('sales/order'), 'delivery', 'text NOT NULL default ""');
$installer->getConnection()->addColumn($this->getTable('sales/order'), 'pickhub', 'text NOT NULL default ""');
$installer->getConnection()->addColumn($this->getTable('sales/order'), 'service', 'text NOT NULL default ""');


/**
 * create rewardpoints table and fields
 */
$installer->run("

DROP TABLE IF EXISTS {$this->getTable('giaohangnhanh/giaohangnhanh')};

CREATE TABLE {$this->getTable('giaohangnhanh/giaohangnhanh')} (
  `id` int(10) unsigned NOT NULL auto_increment,
  `recipient_name` text NULL,
  `delivery_address` text NULL,
  `recipient_phone` text NULL,
  `client_order_code` text NULL,
  `order_code` text NULL,
  `cod_amount` decimal(12,4) NOT NULL default '0',
  `content_note` text NULL,
  `delivery_district_code` text NULL,
  `service_id` varchar(255)  NULL,
  `pick_hub_id` varchar(255)  NULL,
  `weight` decimal(12,4) NOT NULL default '0',
  `length` decimal(12,4) NOT NULL default '0',
  `width` decimal(12,4) NOT NULL default '0',
  `height` decimal(12,4) NOT NULL default '0',
  `estimate_fee` decimal(12,4) NOT NULL default '0',
  `fee` decimal(12,4) NOT NULL default '0',
  `status` varchar(255) NOT NULL default '',
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup();

