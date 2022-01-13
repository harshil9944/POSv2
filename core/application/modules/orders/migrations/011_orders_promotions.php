<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_promotions extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('promotion_total', ORDER_TABLE)) {
            $field = [
                'promotion_total' => ['type' => 'DECIMAL', 'constraint' => '16,2','after'=>'tip','default'=>0]
            ];
            $this->dbforge->add_column(ORDER_TABLE, $field);
        }

        if (!$this->db->field_exists('promotion_total', ORDER_SPLIT_TABLE)) {
            $field = [
                'promotion_total' => ['type' => 'DECIMAL', 'constraint' => '16,2','after'=>'tip','default'=>0]
            ];
            $this->dbforge->add_column(ORDER_SPLIT_TABLE, $field);
        }

        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'order_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'promotion_id' => [
                'type' => 'INT',
                'constraint' => 11
            ]
        ];
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(ORDER_PROMOTION_TABLE, TRUE);

    }

    public function down()
    {

    }
}
