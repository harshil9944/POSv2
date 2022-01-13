<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_split extends CI_Migration {

    public function up() {

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

        if (!$this->db->field_exists('split_type', ORDER_TABLE)) {
            $field = [
                'split_type' => ['type' => 'ENUM("none","equal","item")', 'after' => 'type', 'default'=>'none']
            ];
            $this->dbforge->add_column(ORDER_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
