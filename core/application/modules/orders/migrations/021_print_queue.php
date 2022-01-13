<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_print_queue extends CI_Migration {

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
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_PRINT_QUEUE_TABLE, TRUE);

    }
}
