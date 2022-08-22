<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_spice_level extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('spiciness', ITEM_TABLE)) {
            $field = [
                'is_vegan' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'is_dairy_free' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'is_gluten_free' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'spiciness' => [
                    'type' => 'VARCHAR(20)', 
                    'default'=>'none'
                ],
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
