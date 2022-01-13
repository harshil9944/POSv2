<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_notifications_webpush extends CI_Migration {

    public function up() {

        //Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'endpoint' => [
                'type' => 'TEXT'
            ],
            'key_p256db' => [
                'type' => 'TEXT'
            ],
            'key_auth' => [
                'type' => 'TEXT'
            ],
            'expiration_time' => [
                'type' => 'DATETIME'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(NOTIFICATION_WEBPUSH_TABLE, TRUE);
        //Table End

    }

    public function down()
    {

    }
}
