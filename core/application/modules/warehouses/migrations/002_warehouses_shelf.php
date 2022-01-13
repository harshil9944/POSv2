<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_warehouses_shelf extends CI_Migration {

    public function up() {

        //Warehouse Shelf Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
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
        $this->dbforge->create_table(WAREHOUSE_SHELF_TABLE, TRUE);
        //Warehouse Shelf Table End

    }

    public function down()
    {

    }
}
