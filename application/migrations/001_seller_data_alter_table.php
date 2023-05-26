<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_seller_data_alter_table extends CI_Migration
{
    public function up()
    {
        /* dumping the data into the migrations table */
         $data = [
            [
                'version' => 0,
            ]
        ];
        $this->db->insert_batch('migrations', $data);
        /* adding new fields in seller_data table  */
        $fields = array(
            'slug' => array(
				'type' => 'VARCHAR',
				'constraint' => '512',
				'null' => TRUE,
				'after' => 'user_id'
			)
        );
        $this->dbforge->add_column('seller_data', $fields);
    }

    public function down()
    {
        // Drop column
        $this->dbforge->drop_column('seller_data', 'slug');
    }
}