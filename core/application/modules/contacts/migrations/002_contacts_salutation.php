<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_contacts_salutation extends CI_Migration {

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
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(CONTACT_SALUTATION_TABLE, TRUE);
        //Table End

        $data = [];
        if($this->db->get_where(CONTACT_SALUTATION_TABLE,['id'=>1])->num_rows() === 0) {
            $data[] = [
                'id' => 1,
                'title' => 'Mr.',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($this->db->get_where(CONTACT_SALUTATION_TABLE,['id'=>2])->num_rows() === 0) {
            $data[] = [
                'id' => 2,
                'title' => 'Mrs.',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($this->db->get_where(CONTACT_SALUTATION_TABLE,['id'=>3])->num_rows() === 0) {
            $data[] = [
                'id' => 3,
                'title' => 'Ms.',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($this->db->get_where(CONTACT_SALUTATION_TABLE,['id'=>4])->num_rows() === 0) {
            $data[] = [
                'id' => 4,
                'title' => 'Miss.',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($this->db->get_where(CONTACT_SALUTATION_TABLE,['id'=>5])->num_rows() === 0) {
            $data[] = [
                'id' => 5,
                'title' => 'Dr.',
                'added' => date('Y-m-d H:i:s')
            ];
        }
        if($data) {
            $this->db->insert_batch(CONTACT_SALUTATION_TABLE, $data);
        }

    }

    public function down()
    {

    }
}
