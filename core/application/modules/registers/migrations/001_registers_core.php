<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_registers_core extends CI_Migration {

    public function up() {

        //Register Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
            ],
            'outlet_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'TEXT',
                'constraint' => 100,
            ],
            'primary' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 0
            ],
            'key' => [
                'type' => 'TEXT',
            ],
            'type' => [
                'type' => 'ENUM("Register","Tablet")',
                'default' => 'Register'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(REGISTER_TABLE, TRUE);
        //Register Table End

    }

    public function down()
    {

    }
}
