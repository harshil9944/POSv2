<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_customer_groups extends CI_Migration {

    public function up() {

        //Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'pos_discount' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0
            ],
            'web_discount' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' =>0 
            ],
            'app_discount' => [
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'default' => 0
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(CONTACT_CUSTOMER_GROUPS_TABLE, TRUE);
        //Table End

        $data = [];
        if($this->db->get_where(CONTACT_CUSTOMER_GROUPS_TABLE,['id'=>1])->num_rows() === 0) {
            $data[] = [
                'id' => 1,
                'title' => 'Regular',
                'pos_discount' => 0,
                'web_discount' => 0,
                'app_discount' => 0,
                'status' => 1,
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($data) {
            $this->db->insert_batch(CONTACT_CUSTOMER_GROUPS_TABLE, $data);
        }

    }

    public function down()
    {

    }
}
