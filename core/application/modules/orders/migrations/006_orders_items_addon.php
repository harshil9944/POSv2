<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_addon extends CI_Migration {

    public function up() {

        //Item Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'order_item_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
                'type' => 'ENUM("optional")'
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'quantity'  => [
                'type' => 'DECIMAL',
                'constraint' => '16,0'
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
        $this->dbforge->create_table(ORDER_ITEM_ADDON_TABLE, TRUE);
        //Item Table End

    }

    public function down()
    {

    }
}
