<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_order_bank_transfer_alter_table extends CI_Migration
{
    public function up()
    {

        /* adding new fields in order_bank_transfer table  */
        $fields = array(
            'status' => array(
                'type' => 'TINYINT',
                'constraint' => '2',
                'DEFAULT'    => '0',
                'after' => 'attachments',
                'comment' => '(0:pending|1:rejected|2:accepted)'
            )
        );
        $this->dbforge->add_column('order_bank_transfer', $fields);
    }

    public function down()
    {
        // Drop column
        $this->dbforge->drop_column('order_bank_transfer', 'status');
    }
}