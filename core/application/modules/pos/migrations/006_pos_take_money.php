<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_pos_take_money extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('take_out', POS_SESSION_TABLE)) {
            $field = [
                'take_out' => ['type' => 'DECIMAL','constraint' => '16,2', 'after' => 'closing_cash', 'default'=>0]
            ];
            $this->dbforge->add_column(POS_SESSION_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
