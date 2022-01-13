<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_order_status_deleted extends CI_Migration {

    public function up() {

        $fields = [
            'order_status' => [
                'type' => "ENUM('Draft','Confirmed','Preparing','Ready','Closed','Cancelled','Refunded','Partial Refunded','Deleted')"
            ],
        ];
        $this->dbforge->modify_column(ORDER_TABLE, $fields);

    }
}
