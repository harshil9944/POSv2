<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_part extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('part_no', ORDER_ITEM_TABLE)) {
            $field = [
                'part_no' => ['type' => 'TINYINT(4)', 'after' => 'type', 'default'=>1]
            ];
            $this->dbforge->add_column(ORDER_ITEM_TABLE, $field);
        }
        if (!$this->db->field_exists('printed_qty', ORDER_ITEM_TABLE)) {
            $field = [
                'printed_qty' => ['type' => 'TINYINT(4)', 'after' => 'part_no', 'default'=>0]
            ];
            $this->dbforge->add_column(ORDER_ITEM_TABLE, $field);
        }

    }

    public function down()
    {

    }
}
