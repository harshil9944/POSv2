<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_order_address extends CI_Migration {

    public function up() {
        $delete_order_fields = ['billing_address1','billing_address2','billing_city','billing_state','billing_zip_code','billing_country'];
        foreach ($delete_order_fields as $f){
            if ($this->db->field_exists($f, ORDER_TABLE)) {
                $this->dbforge->drop_column(ORDER_TABLE,$f);
            }
        }

        $fields = [
            'shipping_address1' => [
                'name'       => 'address1',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'shipping_address2' => [
                'name'       => 'address2',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'shipping_city' => [
                'name'       => 'city',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'shipping_state' => [
                'name'       => 'state',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'shipping_zip_code'=>[
                'name'       => 'zip_code',
                'type'       => 'VARCHAR',
                'constraint' => 100,

            ],
            'shipping_country'=>[
                'name'       => 'country',
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ]   
        ];
        $this->dbforge->modify_column(ORDER_TABLE, $fields);

    }
}
