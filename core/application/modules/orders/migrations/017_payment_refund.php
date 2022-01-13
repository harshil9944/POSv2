<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_payment_refund extends CI_Migration {

    public function up() {

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
}
