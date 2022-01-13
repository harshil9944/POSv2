<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_pos_payment_method_app_enabled extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('app_enabled', SYS_PAYMENT_METHOD_TABLE)) {
            $field = [
                'app_enabled' => ['type' => 'TINYINT(1)', 'after' => 'web_enabled', 'default'=>1],
            ];
            $this->dbforge->add_column(SYS_PAYMENT_METHOD_TABLE, $field);
        }

    }

    public function down()
    {

    }
}
