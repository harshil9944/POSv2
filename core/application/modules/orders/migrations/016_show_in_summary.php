<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_show_in_summary extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('show_in_summary', ORDER_SOURCE_TABLE)) {
            $field = [
                'show_in_summary' => ['type' => 'TINYINT(1)', 'after'=>'print_label','default'=>'1']
            ];
            $this->dbforge->add_column(ORDER_SOURCE_TABLE, $field);
        }
        
    }

    public function down()
    {

    }
}
