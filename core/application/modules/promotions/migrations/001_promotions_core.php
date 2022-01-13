<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_promotions_core extends CI_Migration {

    public function up() {

        //Promotion Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'offer_criteria' => [
                'type' => 'TEXT',
            ],
            'offer_type' => [
                'type' => 'ENUM("basic","item","amount")',
                'default' => 'basic'
            ],
            'start_date' => [
                'type' => 'DATETIME'
            ],
            'end_date' => [
                'type' => 'DATETIME'
            ],
            'offer_days' => [
                'type' => 'ENUM("all","weekdays","weekend","custom")',
                'default' => 'all'
            ],
            'offer_custom_days' => [
                'type' => 'TEXT',
            ],
            'customer_type' => [
                'type' => 'ENUM("all","custom")',
                'default' => 'all'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PROMOTIONS_TABLE, TRUE);
        //Promotion Table End

        //Promotion Outlet Table Start
        $fields = [
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'outlet_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PROMOTIONS_OUTLET_TABLE, TRUE);
        //Promotion Outlet Table End

        //Promotion Criteria Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unique' => TRUE,
            ],
            'product_type' => [
                'type' => 'ENUM("all","include","exclude")',
            ],
            'qty_type' => [
                'type' => 'ENUM("fixed","min_max")',
            ],
            'min_qty' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'max_qty' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'min_amount' => [
                'type' => 'DECIMAL(12,2)'
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PROMOTIONS_CRITERIA_TABLE, TRUE);
        //Promotion Criteria Table End

        //Promotion Criteria Product Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'criteria_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sku_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'type' => [
                'type' => 'ENUM("include","exclude")',
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PROMOTIONS_CRI_PRODUCT_TABLE, TRUE);
        //Promotion Criteria Product Table End

        //Promotion Reward Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unique' => TRUE,
            ],
            'product_type' => [
                'type' => 'ENUM("all","include","exclude")',
            ],
            'discount_type' => [
                'type' => 'ENUM("f","p","free")',
            ],
            'discount_value' => [
                'type' => 'DECIMAL(12,2)'
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PROMOTIONS_REWARD_TABLE, TRUE);
        //Promotion Reward Table End

        //Promotion Reward Product Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'reward_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'sku_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0
            ],
            'type' => [
                'type' => 'ENUM("include","exclude")',
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PROMOTIONS_REW_PRODUCT_TABLE, TRUE);
        //Promotion Reward Product Table End

    }

    public function down() {

    }
}
