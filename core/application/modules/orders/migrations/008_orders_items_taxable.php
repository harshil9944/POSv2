<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_taxable extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('taxable', ORDER_ITEM_TABLE)) {
            $field = [
                'taxable' => ['type' => 'TINYINT(1)', 'after' => 'sku_id', 'default'=>'1']
            ];
            $this->dbforge->add_column(ORDER_ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
