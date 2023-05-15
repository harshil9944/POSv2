<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_bookings_status extends CI_Migration {

    public function up() {

        _model('booking_status');

        $data = [
            ['id' => 1, 'title' => 'Pending', 'updated' => sql_now_datetime(), 'added' => sql_now_datetime()],
            ['id' => 2, 'title' => 'Confirmed', 'updated' => sql_now_datetime(), 'added' => sql_now_datetime()],
            ['id' => 3, 'title' => 'Rejected', 'updated' => sql_now_datetime(), 'added' => sql_now_datetime()],
            ['id' => 4, 'title' => 'Completed', 'updated' => sql_now_datetime(), 'added' => sql_now_datetime()],
            ['id' => 5, 'title' => 'Cancelled', 'updated' => sql_now_datetime(), 'added' => sql_now_datetime()],
        ];
        $this->booking_status->insert_batch($data);

    }

    public function down() {

    }
}
