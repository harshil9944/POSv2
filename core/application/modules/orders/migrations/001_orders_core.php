<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_core extends CI_Migration {

    public function up() {

        //Order Table Start
        if (!$this->db->table_exists(ORDER_TABLE)){
            $order_fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'type' => [
                    'type' => 'ENUM("d","p","dine")',
                    'default' => 'd'
                ],
                'order_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ],
                'session_order_no' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unique' => true
                ],
                'source_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'reference_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ],
                'customer_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'billing_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'billing_address1' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'billing_address2' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'billing_city' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'billing_state' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'billing_zip_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                ],
                'billing_country' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'shipping_address1' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'shipping_address2' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'shipping_city' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'shipping_state' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'shipping_zip_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                ],
                'shipping_country' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'warehouse_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'register_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'session_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'salesperson_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'order_date' => [
                    'type' => 'DATETIME'
                ],
                'expected_delivery_date' => [
                    'type' => 'DATETIME'
                ],
                'discount_type' => [
                    'type' => 'ENUM("f","p")',
                    'default' => 'f'
                ],
                'duty_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,4'
                ],
                'freight_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,4'
                ],
                'tax_rate' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10
                ],
                'sub_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'tax_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'change' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2',
                    'null' => TRUE,
                    'default' => 0.00
                ],
                'tip' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2',
                    'null' => TRUE,
                    'default' => 0.00
                ],
                'discount' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2',
                    'null' => TRUE,
                    'default' => 0.00
                ],
                'discount_value' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2',
                    'null' => TRUE,
                    'default' => 0.00
                ],
                'adjustment' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2',
                    'null' => TRUE,
                    'default' => 0.00
                ],
                'grand_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2',
                ],
                'order_status' => [
                    'type' => "ENUM('Draft','Confirmed','Preparing','Ready','Closed','Cancelled')"
                ],
                'cancelled' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'notes' => [
                    'type' => 'TEXT'
                ],
                'added' => [
                    'type' => 'DATETIME'
                ]
            ];

            $this->dbforge->add_field($order_fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_TABLE, TRUE);
            _db_query("ALTER TABLE " . ORDER_TABLE . " ADD UNIQUE INDEX session_order_no (session_order_no, session_id);");
        }
        //Order Table End

        //Order Item Table Start
        $so_item_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'type' => [
                'type' => 'ENUM("single","group")',
                'default' => 'single'
            ],
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
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'sale_unit_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'unit' => [
                'type' => 'VARCHAR',
                'constraint' => 10
            ],
            'sale_unit' => [
                'type' => 'VARCHAR',
                'constraint' => 10
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'sku' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'freight_total' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'duty_total' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'unit_quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'unit_rate' => [
                'type' => 'DECIMAL',
                'constraint' => '16,4'
            ],
            'quantity' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'rate' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'has_spice_level' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'spice_level' => [
                'type' => ORDER_SPICE_LEVELS,
                'default' => ORDER_SPICE_LEVEL_DEFAULT
            ],
            'notes' => [
                'type' => 'TEXT'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($so_item_fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_ITEM_TABLE, TRUE);
        //Order Item Table End

        //Order Payment Table Start
        $order_payment_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'payment_method_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'order_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true
            ],
            'reference_no' => [
                'type' => 'VARCHAR',
                'constraint' => 50
            ],
            'payment_date' => [
                'type' => 'DATETIME'
            ],
            'tip' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'notes' => [
                'type' => 'TEXT'
            ],
            'added' => [
                'type' => 'DATETIME'
            ],
        ];
        $this->dbforge->add_field($order_payment_fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_PAYMENT_TABLE, TRUE);
        //Order Payment Table End

        //SO Source Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => '255'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_SOURCE_TABLE, TRUE);

        $data = [];
        if($this->db->get_where(ORDER_SOURCE_TABLE,['id'=>1])->num_rows() === 0) {
            $data[] = [
                'id' => 1,
                'title' => 'Panel',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($this->db->get_where(ORDER_SOURCE_TABLE,['id'=>2])->num_rows() === 0) {
            $data[] = [
                'id' => 2,
                'title' => 'POS',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($data) {
            $this->db->insert_batch(ORDER_SOURCE_TABLE, $data);
        }
        //SO Source Table End

    }

    public function down()
    {

    }
}
