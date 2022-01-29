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
                'split_type' => [
                    'type' => 'ENUM("none","equal","item")', 
                    'default'=>'none'
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
                'ext_order_no' => [
                    'type' => 'VARCHAR(50)',
                    'default'=>''
                ],
                'source_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'customer_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'outlet_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'billing_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'register_session_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'opening_register_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'close_register_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'session_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'employee_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'order_date' => [
                    'type' => 'DATETIME'
                ],
                'discount_type' => [
                    'type' => 'ENUM("f","p")',
                    'default' => 'f'
                ],
                'duty_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'freight_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'gratuity_rate' => [
                    'type' => 'DECIMAL',
                    'constraint' =>10
                ],
                'gratuity_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'seat_used' => [
                    'type' => 'DECIMAL',
                    'constraint' =>10
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
                'promotion_total' => [
                    'type' => 'DECIMAL', 
                    'constraint' => '16,2',
                    'default'=>0
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
                    'type' => "ENUM('Draft','Confirmed','Preparing','Ready','Closed','Cancelled','Refunded','Partial_refunded','Deleted')"
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
                'type' => 'VARCHAR',
                'constraint' => 10
            ],
            'part_no' => [
                'type' => 'TINYINT(4)',
                'default'=>1
            ],
            'print_location' => [
                'type' => 'VARCHAR(20)',
                'default'=>'default'
            ],
            'printed_qty' => [
                'type' => 'TINYINT(4)',
                 'default'=>0
            ],
            'print_kitchen' => [
                'type' => 'TINYINT(1)',
                 'default'=>1
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100
            ],
            'taxable' => [
                'type' => 'TINYINT(1)', 
                'default'=>'1'
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
                'type'      => 'VARCHAR(20)',
                'default'   => 'medium'
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

         //Order Address Table Start
         $order_address_fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'address1' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'added' => [
                'type' => 'DATETIME'
            ],
        ];
        $this->dbforge->add_field($order_address_fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_ADDRESS_TABLE, TRUE);
        //Order Address Table End

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
            'print_label' => [
                'type' => 'VARCHAR',
                'constraint' => '50'
            ],
            'show_in_summary' => [
                'type' => 'TINYINT(1)',
                'default'=>'1'
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

        if (!$this->db->table_exists(ORDER_PAYMENT_DESC_TABLE)) {
            //Order Payment Description Table Start
            $order_payment_desc_fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'payment_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'transaction_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 255
                ],
                'source_data' => [
                    'type' => 'BLOB'
                ],
                'added' => [
                    'type' => 'DATETIME'
                ],
            ];
            $this->dbforge->add_field($order_payment_desc_fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_PAYMENT_DESC_TABLE, TRUE);
            //Order Payment Description Table End
        }
        if (!$this->db->table_exists(ORDER_PRINT_QUEUE_TABLE)) {
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'order_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'added' => [
                    'type' => 'DATETIME'
                ]
            ];

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_PRINT_QUEUE_TABLE, TRUE);
        }
        if (!$this->db->table_exists(ORDER_ITEM_ADDON_TABLE)) {
            //Item Addon Table Start
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => true
                ],
                'order_item_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'item_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'quantity'  => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,0'
                ],
                'rate' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'added' => [
                    'type' => 'DATETIME'
                ]
            ];

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_ITEM_ADDON_TABLE, TRUE);
            //Item Addon Table End
        }
         //Item Table Start
         $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'order_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->dbforge->create_table(ORDER_ITEM_NOTES_TABLE, TRUE);
        //Item Table End

         //Split Table Start
         if (!$this->db->table_exists(ORDER_SPLIT_TABLE)) {
            $split_fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'title' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ],
                'order_no' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50
                ],
                'order_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'customer_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'billing_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'discount_type' => [
                    'type' => 'ENUM("f","p")',
                    'default' => 'f'
                ],
                'duty_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'freight_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
                ],
                'gratuity_rate' => [
                    'type' => 'DECIMAL',
                    'constraint' =>10
                ],
                'gratuity_total' => [
                    'type' => 'DECIMAL',
                    'constraint' => '16,2'
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
                'promotion_total' => [
                    'type' => 'DECIMAL', 
                    'constraint' => '16,2',
                    'default'=>0
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
                'added' => [
                    'type' => 'DATETIME'
                ]
            ];
            $this->dbforge->add_field($split_fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_SPLIT_TABLE, TRUE);
        }
        //Split Table End

        //Split Item Table Start
        if (!$this->db->table_exists(ORDER_SPLIT_ITEM_TABLE)) {
            $split_fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'split_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'order_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'order_item_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'taxable' => [
                    'type' => 'TINYINT',
                    'constraint' => '1',
                    'default'=>1
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
                'added' => [
                    'type' => 'DATETIME'
                ]
            ];
            $this->dbforge->add_field($split_fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_SPLIT_ITEM_TABLE, TRUE);
        }
        //Split Item Table End

        //Split Payment Table Start
        if (!$this->db->table_exists(ORDER_SPLIT_PAYMENT_TABLE)) {
            $split_fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'split_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'order_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
                'payment_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                ],
            ];
            $this->dbforge->add_field($split_fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(ORDER_SPLIT_PAYMENT_TABLE, TRUE);
        }
        //Split Payment Table End

        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11
            ]
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_PROMOTION_TABLE, TRUE);

        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'payment_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'payment_method_id' => [
                'type' => 'INT',
                'constraint' => 11,
                
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_PAYMENT_REFUND_TABLE, TRUE);


    }

    public function down()
    {

    }
}
