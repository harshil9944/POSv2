<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_customer_custom_fields extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('member_number', CONTACT_CUSTOMER_TABLE)) {
            $field = [
                'member_number' => ['type' => 'VARCHAR','constraint' => '20', 'after' => 'notes', 'default'=>null],
                'full_vaccinated' => [  'type' => 'TINYINT','constraint' => 1,'default' => 0,'after' => 'member_number']
            ];
            $this->dbforge->add_column(CONTACT_CUSTOMER_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
