<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_core extends CI_Migration {

    public function up() {

        //Item Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => FALSE,
                'unique'    => TRUE
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'parent' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'outlet_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'category_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'taxable' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => FALSE,
                'default' => 1
            ],
            'unit_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'image' => [
                'type' => 'TEXT'
            ],
            'icon' => [
                'type' => 'TEXT'
            ],
            'is_addon' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'has_spice_level' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'is_veg' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'print_location' => [
                'type' => 'VARCHAR(20)', 
                'default'=>'default'
            ],
            'rate' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'pos_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'web_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
            ],
            'app_status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
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
        $this->dbforge->create_table(ITEM_TABLE, TRUE);
        //Item Table End

        //Category Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type'      => 'VARCHAR',
                'constraint'=> 100,
                'unique'    => TRUE
            ],
            'sort_order' => [
                'type' => 'INT',
                'constraint' => 4,
                'default' => 0
            ],
            'web_status' => [
                'type' => 'TINYINT(1)', 
                'default'=>'1'
            ],
            'pos_status' => [
                'type' => 'TINYINT(1)', 
                'default'=>'1'
            ],
            'app_status' => [
                'type' => 'TINYINT(1)', 
                'default'=>'1'
            ],
            'parent' => [
                'type' => 'INT',
                'constraint' => 4,
                'default' => 0
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ITEM_CATEGORY_TABLE, TRUE);
        //Category Table End

         //Item Note Table Start
         $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'item_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ITEM_NOTES_TABLE, TRUE);
        //Item Note Table End

          //Item Icon Table End
          $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'added' =>  [
                'type'  =>  'DATETIME'
            ]

        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ITEM_ICON_TABLE, TRUE);
        //Item Icon Table End
    }

    public function down()
    {

    }
}
