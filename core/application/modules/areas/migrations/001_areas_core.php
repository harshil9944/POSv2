<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_areas_core extends CI_Migration {

    public function up() {

        //Areas Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(AREAS_TABLE, TRUE);
        //Areas Table End

        //Tables Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'area_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'short_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'max_seat' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'seat_used' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'ENUM("available","engaged")',
                'default' => 'available'
            ],
            'use_since' => [
                'type' => 'DATETIME'
            ],
            'deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'updated' => [
                'type' => 'DATETIME'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(AREAS_TABLES_TABLE, TRUE);
        //Tables Table End

        //Areas Session Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'table_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'session_start' => [
                'type' => 'DATETIME'
            ],
            'session_end' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(AREAS_SESSION_TABLE, TRUE);
        //Areas Session Table End

        //Areas Relation Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'table_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'session_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(AREAS_RELATION_TABLE, TRUE);
        //Areas Relation Table End

    }

    public function down() {

    }
}
