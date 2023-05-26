<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_brands extends CI_Migration
{

    public function up()
    { /* adding new table brands */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE,
                'NULL'           => FALSE
            ],
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'slug' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'image' => [
                'type'           => 'TEXT',
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('brands');

        $fields = array(
            'brand' => array(
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
        $this->dbforge->drop_table('brands');
        $this->dbforge->drop_column('products', 'brand');
        
    }
}
