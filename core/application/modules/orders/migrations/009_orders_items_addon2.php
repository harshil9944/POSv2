<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_addon2 extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('sku_id', ORDER_ITEM_ADDON_TABLE)) {
            $field = [
                'sku_id' => ['type' => 'INT(11)', 'after' => 'item_id', 'default'=>0]
            ];
            $this->dbforge->add_column(ORDER_ITEM_ADDON_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
