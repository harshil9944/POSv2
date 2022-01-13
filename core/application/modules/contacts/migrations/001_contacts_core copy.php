<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_contacts_core extends CI_Migration {

    public function up() {

        if (!$this->db->table_exists(CONTACT_CUSTOMER_TABLE)) {
            //Customer Table Start
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'auto_increment' => TRUE
                ],
                'customer_id' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10
                ],
                'salutation_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ],
                'first_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'last_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'company_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'display_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
                'email' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'password' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'phone' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                ],
                'baddress_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'saddress_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'designation' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'department' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
                'currency_id' => [
                    'type' => 'INT',
                    'constraint' => 11
                ],
                'currency_code' => [
                    'type' => 'VARCHAR',
                    'constraint' => 5,
                ],
                'price_list_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => TRUE
                ],
                'payment_terms' => [
                    'type' => 'VARCHAR',
                    'constraint' => 5,
                ],
                'notes' => [
                    'type' => 'TEXT'
                ],
                'status' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1
                ],
                'deleted' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0
                ],
                'added' => [
                    'type' => 'DATETIME'
                ]
            ];

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table(CONTACT_CUSTOMER_TABLE, TRUE);
            _db_query("ALTER TABLE " . CONTACT_CUSTOMER_TABLE . " ADD UNIQUE INDEX UK_customer_phone (phone);");
            _db_query("ALTER TABLE " . CONTACT_CUSTOMER_TABLE . " ADD UNIQUE INDEX UK_customer_email (email);");
            //Customer Table End
        }

        //Vendor Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'vendor_id' => [
                'type' => 'VARCHAR',
                'constraint' => 10
            ],
            'salutation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'company_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'display_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'additional_emails' => [
                'type' => 'TEXT'
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'baddress_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'saddress_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'designation' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'department' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'currency_id' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'currency_code' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
            ],
            'price_list_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            ],
            'payment_terms' => [
                'type' => 'VARCHAR',
                'constraint' => 5,
            ],
            'notes' => [
                'type' => 'TEXT'
            ],
            'status' => [
                'type'      =>  'TINYINT',
                'constraint'=>  1,
                'default'   =>  1
            ],
            'deleted' => [
                'type'      =>  'TINYINT',
                'constraint'=>  1,
                'default'   =>  0
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(CONTACT_VENDOR_TABLE, TRUE);
        //Vendor Table End

        //Vendor Additional Contact Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'contact_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            ],
            'salutation_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => TRUE
            ],
            'position' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'department_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(CONTACT_VENDOR_ADDITIONAL_CONTACT_TABLE, TRUE);
        //Vendor Additional Contact Table End

        //Customer Address Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'attention' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address1' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(CONTACT_CUSTOMER_ADDRESS_TABLE, TRUE);
        //Customer Address Table End

        //Vendor Address Table Start
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ],
            'attention' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address1' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'address2' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'added' => [
                'type' => 'DATETIME'
            ]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table(CONTACT_VENDOR_ADDRESS_TABLE, TRUE);
        //Vendor Address Table End

    }

    public function down()
    {

    }
}
