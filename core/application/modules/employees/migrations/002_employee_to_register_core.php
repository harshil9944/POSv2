<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_employee_to_register_core extends CI_Migration {

    public function up() {

        //Employee to Register
        $fields = [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'register_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->add_key('register_id', TRUE);
        $this->dbforge->create_table(EMPLOYEE_TO_REGISTER_TABLE, TRUE);
        //Employee to Register
       
    }

    public function down() {

    }
}
