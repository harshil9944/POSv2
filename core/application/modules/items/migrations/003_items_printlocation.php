<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_printlocation extends CI_Migration {

    public function up() {

        if ($this->db->field_exists('print_kitchen', ITEM_TABLE)) {
            $this->dbforge->drop_column(ITEM_TABLE,'print_kitchen');
        }
        if (!$this->db->field_exists('print_location', ITEM_TABLE)) {
            $field = [
                'print_location' => ['type' => 'VARCHAR(20)', 'after' => 'taxable', 'default'=>'default']
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
