<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_pos_update_payment_method extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('type', SYS_PAYMENT_METHOD_TABLE)) {
            $field = [
                'type' => ['type' => 'ENUM("auto","manual")', 'after' => 'code', 'default'=>'manual'],
                'auto_discount_value' => ['type' => 'DECIMAL(10,2)', 'after' => 'type', 'default'=>0],
                'pos_enabled' => ['type' => 'TINYINT(1)', 'after' => 'auto_discount_value', 'default'=>1],
                'web_enabled' => ['type' => 'TINYINT(1)', 'after' => 'pos_enabled', 'default'=>1],
            ];
            $this->dbforge->add_column(SYS_PAYMENT_METHOD_TABLE, $field);
        }

        if (!$this->db->field_exists('is_cash', SYS_PAYMENT_METHOD_TABLE)) {
            $field = [
                'is_cash' => ['type' => 'TINYINT(1)', 'after' => 'web_enabled', 'default'=>0],
            ];
            $this->dbforge->add_column(SYS_PAYMENT_METHOD_TABLE, $field);
        }

    }

    public function down()
    {

    }
}
