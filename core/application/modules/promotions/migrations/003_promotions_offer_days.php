<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_promotions_offer_days extends CI_Migration {

    public function up() {
        $field = [
            'offer_days' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
        ];
        $this->dbforge->modify_column(PROMOTIONS_TABLE, $field);
    }

    public function down() {

    }
}
