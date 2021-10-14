<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_SpecialPriceDateTime
 * @copyright  Copyright (c) 2021 Landofcoder (https://landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\SpecialPriceDateTime\Setup;

use Magento\Framework\Setup\UninstallInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

/**
 * Class Uninstall
 * @package Lof\SpecialPriceDateTime\Setup
 */
class Uninstall implements UninstallInterface
{
    /**
     * Eav setup factory
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Init
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(\Magento\Eav\Setup\EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function uninstall(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create();

        $connection = $setup->getConnection();
        $table = $setup->getTable('eav_attribute');
        $entity_type_id = $eavSetup->getEntityTypeId('catalog_product');
        $data = [
            'frontend_input' => 'date',
            'frontend_model' => null
        ];

        //update attribute special_from_date
        //backend_model: Magento\Catalog\Model\Attribute\Backend\Startdate
        $bind = [
            'attribute_code' => 'special_from_date',
            'entity_type_id' => $entity_type_id
        ];
        $select = $connection->select()->from(
            $table,
            'attribute_id'
        )->where(
            'attribute_code = :attribute_code AND entity_type_id = :entity_type_id'
        );
        $attribute_id = $connection->fetchOne($select, $bind);
        if ($attribute_id) {
            $where = ['attribute_id =?' => $attribute_id];
            $connection->update($table, $data, $where);
        }
        //update attribute special_to_date
        $bind = [
                    'attribute_code' => 'special_to_date',
                    'entity_type_id' => $entity_type_id
                ];
        $select = $connection->select()->from(
            $table,
            'attribute_id'
        )->where(
            'attribute_code = :attribute_code AND entity_type_id = :entity_type_id'
        );
        $attribute_id = $connection->fetchOne($select, $bind);
        if ($attribute_id) {
            $where = ['attribute_id =?' => $attribute_id];
            $connection->update($table, $data, $where);
        }
        $setup->endSetup();
    }
}
