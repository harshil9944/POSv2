<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_ext_order_no extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('ext_order_no', ORDER_TABLE)) {
            $field = [
                'ext_order_no' => ['type' => 'VARCHAR(50)','after'=>'session_order_no','default'=>'']
            ];
            $this->dbforge->add_column(ORDER_TABLE, $field);
        }

    }

    public function down() {

    }
}
