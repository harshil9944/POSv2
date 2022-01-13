<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_payment_desc extends CI_Migration {

    public function up() {

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

    }

    public function down()
    {

    }
}
