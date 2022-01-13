<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_printtokitchen extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('print_kitchen', ITEM_TABLE)) {
            $field = [
                'print_kitchen' => ['type' => 'TINYINT(1)', 'after' => 'taxable', 'default'=>1]
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }

    }

    public function down()
    {

    }
}
