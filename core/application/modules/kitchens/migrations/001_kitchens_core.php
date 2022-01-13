<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_kitchens_core extends CI_Migration {

    public function up() {

       
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
           
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'printer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'template_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
           
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(KITCHENS_TABLE, TRUE);

    }

    public function down() {

    }
}
