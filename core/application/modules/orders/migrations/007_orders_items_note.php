<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_items_note extends CI_Migration {

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
        $this->dbforge->create_table(ORDER_ITEM_NOTES_TABLE, TRUE);
        //Item Table End

    }

    public function down()
    {

    }
}
