<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_category2 extends CI_Migration {

    public function up() {

    
        $field = [
            'app_status' => ['type' => 'TINYINT(1)', 'default'=>'1']
        ];
        $this->dbforge->add_column(ITEM_TABLE, $field);
        $this->dbforge->add_column(ITEM_CATEGORY_TABLE, $field);
        
    }

    public function down()
    {   

    }
}
