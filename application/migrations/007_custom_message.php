<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_custom_message extends CI_Migration
{
    public function up()
    {
        $fields = array(
            'title' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '2048',
                'NULL'           => TRUE,
            ),
            'message' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '4096',
                'NULL'           => TRUE,
            )
        );
        $this->dbforge->modify_column('custom_notifications', $fields);
        $fields = array(
            'order_item_id' => array(
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE,
                'after'          => 'order_id'
            ),
            'is_refund' => array(
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
                'default'        => '0',
                'after'          => 'date_created'
            )
        );
        $this->dbforge->add_column('transactions', $fields);

        $fields = array(
            'send_to' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '64',
                'NULL'           => TRUE,
                'after'          => 'type_id'
            ),
            'users_id' => array(
                'type'           => 'TEXT',
                'NULL'           => TRUE,
                'after'          => 'send_to'
            )
        );
        $this->dbforge->add_column('notifications', $fields);

        $fields = array(
            'title' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '2048',
                'NULL'           => TRUE,
            ),
        );
        $this->dbforge->modify_column('custom_notifications', $fields);

        
    }
}
