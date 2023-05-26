<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_cash_collection extends CI_Migration
{
    public function up()
    {

        /* adding new fields in orders table */
        $fields = array(
            'cash_received' => array(
                'type'           => 'DOUBLE',
                'constraint'     => '15,2',
                'NULL'           => FALSE,
                'default'        => 0.00,
                'after'          => 'bonus'
            )
        );
        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        // Drop columns >> $this->dbforge->drop_column('table_name', 'column_to_drop');
        $this->dbforge->drop_column('users', 'cash_received');
    }
}
