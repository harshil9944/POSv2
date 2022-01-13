<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_split_item_taxable extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('taxable', ORDER_SPLIT_ITEM_TABLE)) {
            $field = [
                'taxable' => ['type' => 'TINYINT', 'constraint' => '1','after'=>'order_item_id','default'=>1]
            ];
            $this->dbforge->add_column(ORDER_SPLIT_ITEM_TABLE, $field);
        }

    }

    public function down() {

    }
}
