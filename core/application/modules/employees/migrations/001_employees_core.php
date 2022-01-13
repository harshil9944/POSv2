<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_employees_core extends CI_Migration {

    public function up() {

        //Employee to Warehouse/Outlet Table Start
        $fields = [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'warehouse_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('user_id', TRUE);
        $this->dbforge->add_key('warehouse_id', TRUE);
        $this->dbforge->create_table(EMPLOYEE_TO_WAREHOUSE_TABLE, TRUE);
        //Employee to Warehouse/Outlet Table End

    }

    public function down() {

    }
}
