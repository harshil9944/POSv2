<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_employees_core extends CI_Migration {

    public function up() {

        //Employee to Warehouse/Outlet Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'outlet_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ], 
             'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'mobile' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default'=>1
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default'=>0
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(EMPLOYEE_TABLE, TRUE);
        //Employee to Warehouse/Outlet Table End

         //Employee Shift Table Start
         $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'outlet_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'employee_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'opening_register_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'close_register_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'take_out' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'default'=>0
            ],
            'start_shift' => [
                'type' => 'DATETIME',
            ],
            'end_shift' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(EMPLOYEE_SHIFT_TABLE, TRUE);
        //Employee Shift Table End

    }

    public function down() {

    }
}
