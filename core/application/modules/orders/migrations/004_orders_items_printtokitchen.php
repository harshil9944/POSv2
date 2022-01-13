<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_printtokitchen extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('print_kitchen', ORDER_ITEM_TABLE)) {
            $field = [
                'print_kitchen' => ['type' => 'TINYINT(1)', 'after' => 'printed_qty', 'default'=>1]
            ];
            $this->dbforge->add_column(ORDER_ITEM_TABLE, $field);
        }

    }

    public function down()
    {

    }
}
