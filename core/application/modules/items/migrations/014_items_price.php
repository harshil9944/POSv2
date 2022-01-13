<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_price extends CI_Migration {

    public function up() {

    
        $field = [
            'sale_price' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
            'purchase_price' => [
                'type' => 'DECIMAL',
                'constraint' => '16,2'
            ],
        ];
        $this->dbforge->modify_column(ITEM_PRICE_TABLE, $field);
      
        
    }

    public function down()
    {   

    }
}
