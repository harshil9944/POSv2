<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_printers_core extends CI_Migration {

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
            'status' => [
                'type' => 'TINYINT',             
            ],
            'type' =>[
                'type' =>  'ENUM("usb","network","serial")',
            ],
            'port' => [
                'type' => 'VARCHAR',
                'constraint' => 7,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'open_cash_drawer' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(PRINTERS_TABLE, TRUE);

    }

    public function down() {

    }
}
