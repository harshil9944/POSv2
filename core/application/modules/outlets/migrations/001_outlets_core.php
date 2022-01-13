<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_outlets_core extends CI_Migration {

    public function up() {

        //Warehouse Table Start
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
            'type' => [
                'type' => 'ENUM("warehouse","outlet")',
                'default' => 'warehouse'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address_1' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address_2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'country_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'state_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'zipcode' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(OUTLET_TABLE, TRUE);
        //Warehouse Table End

    }

    public function down()
    {

    }
}
