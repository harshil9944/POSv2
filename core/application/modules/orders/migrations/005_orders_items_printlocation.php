<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_printlocation extends CI_Migration {

    public function up() {

        if ($this->db->field_exists('print_kitchen', ORDER_ITEM_TABLE)) {
            $this->dbforge->drop_column(ORDER_ITEM_TABLE,'print_kitchen');
        }
        if (!$this->db->field_exists('print_location', ORDER_ITEM_TABLE)) {
            $field = [
                'print_location' => ['type' => 'VARCHAR(20)', 'after' => 'part_no', 'default'=>'default']
            ];
            $this->dbforge->add_column(ORDER_ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
