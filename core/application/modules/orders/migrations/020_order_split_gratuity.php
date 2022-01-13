<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_order_split_gratuity extends CI_Migration {
    public function up() {

        if (!$this->db->field_exists('gratuity', ORDER_SPLIT_TABLE)) {
            $field = [
                'gratuity_rate' => ['type' => 'VARCHAR','constraint' => 10, 'after'=>'freight_total','default'=>'0'],
                'gratuity_total' => ['type' => 'DECIMAL','constraint' => '16,2', 'after'=>'gratuity_rate','default'=>'0'],
            ];
            $this->dbforge->add_column(ORDER_SPLIT_TABLE, $field);
        }

    }

}
