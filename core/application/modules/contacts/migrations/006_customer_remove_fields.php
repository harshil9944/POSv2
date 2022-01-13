<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_customer_remove_fields extends CI_Migration {

    public function up() {

        $delete_country_fields = ['iso_code_2','iso_code_3','address_format','postcode_required'];
        foreach ($delete_country_fields as $f){
            if ($this->db->field_exists($f, COUNTRY_TABLE)) {
                $this->dbforge->drop_column(COUNTRY_TABLE,$f);
            }
        }
        $delete_customer_fields = ['salutation_id','company_name','baddress_id','saddress_id','designation','department','currency_id','currency_code','price_list_id','payment_terms'];
        foreach ($delete_customer_fields as $c){
            if ($this->db->field_exists($c, CONTACT_CUSTOMER_TABLE)) {
                $this->dbforge->drop_column(CONTACT_CUSTOMER_TABLE,$c);
            }
        }
        if ($this->db->field_exists('phone', CONTACT_CUSTOMER_ADDRESS_TABLE)) {
            $this->dbforge->drop_column(CONTACT_CUSTOMER_ADDRESS_TABLE,'phone');
        }

         $fields = [
            'attention' => [
                'name'      => 'title',
                'type'      => 'VARCHAR(50)',
                'null'      => true
            ],
            'city' => [
                'name'      => 'city_id',
                'type' => 'INT',
                'constraint' => 11
            ],
            'state' => [
                'name'     => 'state_id',
                'type' => 'INT',
                'constraint' => 11
            ],
            'country' => [
                'name'  => 'country_id',
                'type' => 'INT',
                'constraint' => 11
            ],

        ];
        $this->dbforge->modify_column(CONTACT_CUSTOMER_ADDRESS_TABLE, $fields);

        if (!$this->db->field_exists('group_id', CONTACT_CUSTOMER_TABLE)) {
            $field = [
                'group_id' => ['type' => 'INT','constraint' => '11', 'after' => 'customer_id'],
                'default_address_id' => [ 'type' => 'INT','constraint' => '11','after' => 'group_id']
            ];
            $this->dbforge->add_column(CONTACT_CUSTOMER_TABLE, $field);
        }

        _db_query("UPDATE con_customer cc SET cc.group_id = 1 ");

        if (!$this->db->field_exists('customer_id', CONTACT_CUSTOMER_ADDRESS_TABLE)) {
            $field = [
                'customer_id' => ['type' => 'INT','constraint' => '11', 'after' => 'id'],
            ];
            $this->dbforge->add_column(CONTACT_CUSTOMER_ADDRESS_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
