<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_items_description extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('description', ITEM_TABLE)) {
            $field = [
                'description' => ['type' => 'LONGTEXT', 'after' => 'title'],
            ];
            $this->dbforge->add_column(ITEM_TABLE, $field);
        }
    }

    public function down()
    {

    }
}
