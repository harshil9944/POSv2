<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_payment_method extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('source_id', SYS_PAYMENT_METHOD_TABLE)) {
            $field = [
                'source_id' => ['type' => 'INT(11)', 'after' => 'title', 'default'=>0]
            ];
            $this->dbforge->add_column(SYS_PAYMENT_METHOD_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
