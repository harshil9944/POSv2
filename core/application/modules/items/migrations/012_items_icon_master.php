<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_icon_master extends CI_Migration {

    public function up() {

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
