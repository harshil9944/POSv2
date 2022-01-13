<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_areas_sortorder extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('sort_order', AREAS_TABLE)) {
            $field = [
                'sort_order' => ['type' => 'TINYINT(4)', 'after' => 'description', 'default'=>99]
            ];
            $this->dbforge->add_column(AREAS_TABLE, $field);
        }
        
        $field = [
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 11,
                'default'=> 0
            ],
            
        ];
        $this->dbforge->modify_column(AREAS_TABLE, $field);

        if (!$this->db->field_exists('sort_order', AREAS_TABLES_TABLE)) {
            $field = [
                'sort_order' => ['type' => 'INT','constraint' => 11, 'after' => 'description', 'default'=>0]
            ];
            $this->dbforge->add_column(AREAS_TABLES_TABLE, $field);
        }
        
    }

    public function down()
    {

    }
}
