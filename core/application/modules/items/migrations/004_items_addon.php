<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_addon extends CI_Migration {

    public function up() {

        //Item Table Start
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
            'addon_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'type' => [
                'type' => 'ENUM("optional")',
                'default' => 'optional',
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'sale_price' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ITEM_ADDON_TABLE, TRUE);
        //Item Table End

    }

    public function down()
    {

    }
}
