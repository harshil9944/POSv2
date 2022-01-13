<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_orders_dynamic_spicelevels extends CI_Migration {

    public function up() {

        $fields = [
            'spice_level' => [
                'name'      => 'spice_level',
                'type'      => 'VARCHAR(20)',
                'default'   => 'medium'
            ],
        ];
        $this->dbforge->modify_column(ORDER_ITEM_TABLE, $fields);

    }

    public function down() {

    }
}
