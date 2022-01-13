<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_is_addon extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('is_addon', ITEM_TABLE)) {
            $field = [
                'is_addon' => ['type' => 'TINYINT(1)', 'after' => 'icon', 'default'=>'0']
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
