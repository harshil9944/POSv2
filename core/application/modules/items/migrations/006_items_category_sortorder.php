<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_category_sortorder extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('sort_order', ITEM_CATEGORY_TABLE)) {
            $field = [
                'sort_order' => ['type' => 'TINYINT(4)', 'after' => 'parent', 'default'=>99]
            ];
            $this->dbforge->add_column(ITEM_CATEGORY_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
