<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_notifications_core extends CI_Migration {

    public function up() {

        //Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'type_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'url' => [
                'type' => 'TEXT'
            ],
            'title' => [
                'type'      => 'VARCHAR',
                'constraint'=>  255
            ],
            'data' => [
                'type' => 'TEXT'
            ],
            'read_at' => [
                'type' => 'DATETIME'
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint'=>  1,
                'default'=>0
            ],
            'deleted_at' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(NOTIFICATION_TABLE, TRUE);
        //Table End

    }

    public function down()
    {

    }
}
