<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_icon extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('icon', ITEM_TABLE)) {
            $field = [
                'icon' => ['type' => 'VARCHAR(50)', 'after' => 'image', 'default'=>'']
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
