<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_bookings_core extends CI_Migration {

    public function up() {

        //Booking Status Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title'  => [
                'type' => 'VARCHAR',
                'constraint' => 255
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
        $this->dbforge->create_table(BOOKINGS_STATUS_TABLE, TRUE);
        //Booking Status Table End

        //Booking Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'date'  => [
                'type' => 'DATE'
            ],
            'start_time' => [
                'type' => 'TIME'
            ],
            'end_time' => [
                'type' => 'TIME'
            ],
            'number_of_person' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'area_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'booking_name'=> [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'customer_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'menu' => [
                'type' => 'TEXT',
            ],
            'remark' => [
                'type' => 'TEXT',
            ],
            'advance' => [
                'type' => 'FLOAT',
                'decimal' => '8,2'
            ],
            'status' => [
                'type' => 'INT',
                'constraint' => 11,
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
        $this->dbforge->create_table(BOOKINGS_TABLE, TRUE);
        //Booking Table End
    }

    public function down() {

    }
}
