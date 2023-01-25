<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_promotions_sd_and_ed extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('start_time', PROMOTIONS_TABLE)) {
            $field = [
                'start_time' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => null,
                    'after'=>'end_date'
                ],
                
            ];
            $this->dbforge->add_column(PROMOTIONS_TABLE, $field);
        }
        if (!$this->db->field_exists('end_time', PROMOTIONS_TABLE)) {
            $field = [
                'end_time' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => null,
                    'after'=>'start_time'
                ],
                
            ];
            $this->dbforge->add_column(PROMOTIONS_TABLE, $field);
        }
    }

    public function down() {

    }
}
