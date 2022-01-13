<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_warehouses_batch extends CI_Migration {

    public function up() {

        //Warehouse Batch Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'shelf_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'manufacturer_batch_no' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'manufactured_date' => [
                'type' => 'DATETIME'
            ],
            'expiry_date' => [
                'type' => 'DATETIME'
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
        $this->dbforge->create_table(WAREHOUSE_BATCH_TABLE, TRUE);
        //Warehouse Batch Table End

    }

    public function down()
    {

    }
}
