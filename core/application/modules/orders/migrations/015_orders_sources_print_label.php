<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_sources_print_label extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('print_label', ORDER_SOURCE_TABLE)) {
            $field = [
                'print_label' => ['type' => 'VARCHAR', 'constraint' => '50','after'=>'title']
            ];
            $this->dbforge->add_column(ORDER_SOURCE_TABLE, $field);
        }

    }

    public function down() {

    }
}
