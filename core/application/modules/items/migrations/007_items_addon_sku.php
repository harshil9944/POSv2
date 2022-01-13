<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_addon_sku extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('sku_id', ITEM_ADDON_TABLE)) {
            $field = [
                'sku_id' => ['type' => 'INT(11)', 'after' => 'item_id', 'default'=>0]
            ];
            $this->dbforge->add_column(ITEM_ADDON_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
