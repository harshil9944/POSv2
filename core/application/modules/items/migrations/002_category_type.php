<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_category_type extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('type', ITEM_CATEGORY_TABLE)) {
            $field = [
                'type' => ['type' => 'ENUM("liquor","food")', 'after' => 'sort_order', 'default'=>'food'],
            ];
            $this->dbforge->add_column(ITEM_CATEGORY_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
