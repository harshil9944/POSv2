<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_order_print_queue extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('printing', ORDER_PRINT_QUEUE_TABLE)) {
            $field = [
                'printing' => [ 'type' => 'TINYINT',
                'constraint' => '1',
                'default'=>0,
                'after'=>'order_id'
                ]
            ];
            $this->dbforge->add_column(ORDER_PRINT_QUEUE_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
