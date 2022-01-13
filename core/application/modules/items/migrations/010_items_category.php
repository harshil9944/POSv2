<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_category extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('web_status', ITEM_TABLE)) {
            $field = [
                'web_status' => ['type' => 'TINYINT(1)', 'after'=>'has_spice_level','default'=>'1']
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
        if (!$this->db->field_exists('pos_status', ITEM_TABLE)) {
            $field = [
                'pos_status' => ['type' => 'TINYINT(1)', 'after'=>'web_status', 'default'=>'1'],
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
