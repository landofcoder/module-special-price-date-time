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

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory
        ) 
    { 
        $this->eavSetupFactory = $eavSetupFactory; 
    } 

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $connection = $setup->getConnection();
        $table = $setup->getTable('eav_attribute');
        $data = [
            'frontend_input' => 'datetime',
            'frontend_model' => \Magento\Eav\Model\Entity\Attribute\Frontend\Datetime::class
        ];
        $entity_type_id = $eavSetup->getEntityTypeId('catalog_product');
        //update attribute special_from_date
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

