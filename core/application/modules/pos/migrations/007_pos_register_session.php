<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_pos_register_session extends CI_Migration {

    public function up() {

        //Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'register_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'opening_user_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'closing_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'opening_date' => [
                'type' => 'DATETIME'
            ],
            'closing_date' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'opening_cash' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'closing_cash' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'null' => true
            ],
            'take_out' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2',
                'default'=>0
            ],
            'opening_note' => [
                'type' => 'TEXT'
            ],
            'closing_note' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'ENUM("Open","Close")',
                'default' => 'Open'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(POS_REGISTER_SESSION_TABLE, TRUE);
        //Table End

    }

    public function down()
    {

    }
}
