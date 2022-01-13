<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_core extends CI_Migration {

    public function up() {

        //Item Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => FALSE,
                'unique'    => TRUE
            ],
            'type' => [
                'type' => 'ENUM("single","group")',
                'default' => 'single'
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'taxable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1
            ],
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'purchase_unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sale_unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'manufacturer' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'vendor_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'image' => [
                'type' => 'TEXT'
            ],
            'has_spice_level' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ITEM_TABLE, TRUE);
        //Item Table End

        //Options Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            /*'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],*/
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->create_table(OPTION_TABLE, TRUE);
        //Options Table End

        //Option Values Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'option_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->add_key('option_id', TRUE);
        $this->dbforge->create_table(OPTION_VALUE_TABLE, TRUE);
        //Option Values Table End

        //Category Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type'      => 'VARCHAR',
                'constraint'=> 100,
                'unique'    => TRUE
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'parent' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ITEM_CATEGORY_TABLE, TRUE);
        //Category Table End

        //Features Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(FEATURE_TABLE, TRUE);
        //Features Table End

        //Feature Values Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'feature_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->add_key('feature_id', TRUE);
        $this->dbforge->create_table(FEATURE_VALUE_TABLE, TRUE);
        //Feature Values Table End

        //Item SKUs Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'is_veg' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'upc' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'ean' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'weight' => [
                'type' => 'DECIMAL',
                'constraint' => '15,8'
            ],
            'reorder_level' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->create_table(ITEM_SKU_TABLE, TRUE);
        //Item SKUs Table End

        //Item Inventory Table Start
        $fields = [
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'order_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'sku_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'reason' => [
                'type' => 'ENUM("opening","purchase","sale","transferIn","transferOut")'
            ],
            'date' => [
                'type' => 'datetime'
            ],
            'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'null' => FALSE,
                'default' => 0.00
            ],
            'rate' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'null' => TRUE
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'null' => TRUE
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->create_table(ITEM_INVENTORY_TABLE, TRUE);
        //Item Inventory Table End

        //Item Stock Table Start
        $fields = [
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'sku_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'on_hand' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'null' => FALSE,
                'default' => 0.00
            ],
            'modified' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->add_key('sku_id', TRUE);
        $this->dbforge->add_key('warehouse_id', TRUE);
        $this->dbforge->create_table(ITEM_STOCK_TABLE, TRUE);
        //Item Stock Table End

        //SKU Values Table Start
        $fields = [
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'sku_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'option_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'value_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->add_key('sku_id', TRUE);
        $this->dbforge->add_key('option_id', TRUE);
        $this->dbforge->add_key('value_id', TRUE);
        $this->dbforge->create_table(SKU_VALUE_TABLE, TRUE);
        //SKU Values Table End

        //Item Price Table Start
        $fields = [
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'sku_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'conversion_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'purchase_currency' => [
                'type' => 'VARCHAR',
                'constraint' => 5
            ],
            'sale_currency' => [
                'type' => 'VARCHAR',
                'constraint' => 5
            ],
            'purchase_price' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'sale_price' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('item_id', TRUE);
        $this->dbforge->add_key('sku_id', TRUE);
        $this->dbforge->add_key('unit_id', TRUE);
        $this->dbforge->create_table(ITEM_PRICE_TABLE, TRUE);
        //Item Price Table End

    }

    public function down()
    {

    }
}
