<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_hsn_code extends CI_Migration
{

    public function up()
    {
        $fields = array(
            'hsn_code' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE,
                'after'          => 'made_in'
            ),

        );
        $this->dbforge->add_column('products', $fields);
    }
    public function down()
    {
        $this->dbforge->drop_column('products', 'hsn_code');
    }
}
