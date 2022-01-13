<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_contacts_email_mobile_null extends CI_Migration {

    public function up() {

        $fields = [
            'email' => [
                'name'      => 'email',
                'type'      => 'VARCHAR(50)',
                'null'      => true
            ],
        ];
        $this->dbforge->modify_column(CONTACT_CUSTOMER_TABLE, $fields);

        $fields = [
            'phone' => [
                'name'      => 'phone',
                'type'      => 'VARCHAR(50)',
                'null'      => true
            ],
        ];
        $this->dbforge->modify_column(CONTACT_CUSTOMER_TABLE, $fields);

    }

    public function down() {

    }
}
