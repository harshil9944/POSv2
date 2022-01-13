<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_warehouses_internal_vc extends CI_Migration {

    public function up() {

        if (!$this->db->field_exists('vendor_id', WAREHOUSE_TABLE)) {
            $warehouse_add_fields = [
                'vendor_id' => ['type' => 'INT(11)', 'after' => 'code', 'default' => 0],
                'customer_id' => ['type' => 'INT(11)', 'after' => 'code', 'default' => 0]
            ];
            $this->dbforge->add_column(WAREHOUSE_TABLE, $warehouse_add_fields);
        }

    }

    public function down()
    {

    }
}
