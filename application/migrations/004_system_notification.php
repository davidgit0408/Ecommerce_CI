<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_system_notification extends CI_Migration
{

    public function up()
    {
        /* alter field in promo_codes table */
        $fields = array(
            'is_cashback' => array(
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
                'default'        => 0,
                'after'          => 'status'
            ), 'list_promocode' => array(
                'type' => 'TINYINT',
                'constraint' => '4',
                'DEFAULT'     => '1',
                'after' => 'is_cashback'
            ),
        );
        $this->dbforge->add_column('promo_codes', $fields);


        /* adding new table system_notification */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'message' => [
                'type'           => 'VARCHAR',
                'constraint'     => '20',
                'NULL'           => TRUE
            ],
            'type' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'type_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE,
                'default'        => 0,
            ],
            'read_by' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'default'        => 0,
            ],
            'date_sent TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('system_notification');
    }
    public function down()
    {
        $this->dbforge->drop_table('system_notification');
        $this->dbforge->drop_column('promo_codes', 'is_cashback');
        $this->dbforge->drop_column('promo_codes', 'list_promocode');
    }
}
