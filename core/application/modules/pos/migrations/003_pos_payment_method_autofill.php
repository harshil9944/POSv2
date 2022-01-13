<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_pos_payment_method_autofill extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('autofill_amount', SYS_PAYMENT_METHOD_TABLE)) {
            $field = [
                'autofill_amount' => ['type' => 'TINYINT(1)', 'after' => 'is_cash', 'default'=>0],
            ];
            $this->dbforge->add_column(SYS_PAYMENT_METHOD_TABLE, $field);
        }

    }

    public function down()
    {

    }
}
