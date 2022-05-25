<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Migration_clover_payment extends CI_Migration {

    public function up() {

        if (!$this->db->table_exists(ORDER_CLOVER_PAYMENT)){

            $fields = [
                'id'         => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => TRUE,
                ],
                'order_id'   => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'payment_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'row'     => [
                    'type' => 'LONGTEXT',
                ],
                'added'      => [
                    'type' => 'DATETIME',
                ],
            ];

            $this->dbforge->add_field( $fields );
            $this->dbforge->add_key( 'id', TRUE );
            $this->dbforge->create_table( ORDER_CLOVER_PAYMENT, TRUE );
        }

        if (!$this->db->table_exists(ORDER_CLOVER_REFUND_PAYMENT)){

            $fields = [
                'id'         => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => TRUE,
                ],
                'order_id'   => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'payment_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'row'     => [
                    'type' => 'LONGTEXT',
                ],
                'added'      => [
                    'type' => 'DATETIME',
                ],
            ];

            $this->dbforge->add_field( $fields );
            $this->dbforge->add_key( 'id', TRUE );
            $this->dbforge->create_table( ORDER_CLOVER_REFUND_PAYMENT, TRUE );
        }   

    }
}
