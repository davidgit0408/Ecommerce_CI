<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_countries extends CI_Migration
{

    public function up()
    {
        $sql = file_get_contents(base_url('countries.sql'));
        $explode = explode(';', $sql);
        for ($i = 0; $i < count($explode) - 1; $i++) {
            $this->db->query($explode[$i]);
        }

        $fields = array(
            'admin_commission_amount' => array(
                'type'           => 'DOUBLE',
                'constraint'     => '15,2',
                'NULL'           => FALSE,
                'default'        => 0,
                'after'          => 'status'
            ), 'seller_commission_amount' => array(
                'type' => 'DOUBLE',
                'constraint' => '15,2',
                'NULL'   => FALSE,
                'DEFAULT'     => '1',
                'after' => 'admin_commission_amount'
            ),
        );
        $this->dbforge->add_column('order_items', $fields);
    }
    public function down()
    {
        $this->dbforge->drop_table('countries');
        $this->dbforge->drop_column('order_items', 'admin_commission_amount');
        $this->dbforge->drop_column('order_items', 'seller_commission_amount');
    }
}
