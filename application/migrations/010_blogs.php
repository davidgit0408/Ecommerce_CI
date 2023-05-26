<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_blogs extends CI_Migration
{

    public function up()
    { 
        /* adding new table blogs */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE,
                'NULL'           => FALSE
            ],
            'category_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE
            ],
            'title' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'description' => [
                'type'           => 'MEDIUMTEXT',
                'NULL'           => TRUE
            ],
            'image' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'slug' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
            ],
            'date_added TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('blogs');

        /* adding new table digital_orders_mails */
        $this->dbforge->add_field([
            'id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'auto_increment' => TRUE,
                'NULL'           => FALSE
            ],
            'order_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE
            ],
            'order_item_id' => [
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => TRUE
            ],
            'subject' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'message' => [
                'type'           => 'VARCHAR',
                'constraint'     => '256',
                'NULL'           => TRUE
            ],
            'file_url' => [
                'type'           => 'VARCHAR',
                'constraint'     => '512',
                'NULL'           => TRUE
            ],
            'date_added TIMESTAMP default CURRENT_TIMESTAMP',
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('digital_orders_mails');

        /* adding new table blog_categories */
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
            'banner' => [
                'type'           => 'TEXT',
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
            ],
        ]);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('blog_categories');

        $fields = array(
            'download_allowed' => array(
                'type'           => 'INT',
                'constraint'     => '11',
                'NULL'           => FALSE,
                'default'        => '0',
                'after'          => 'cod_allowed'
            ),
            'download_type' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '40',
                'NULL'           => TRUE,
                'after'          => 'download_allowed'
            ),
            'download_link' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '512',
                'NULL'           => TRUE,
                'after'          => 'download_type'
            ),
            
        );
        $this->dbforge->add_column('products', $fields);

        $fields = array(
            'email' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '254',
                'NULL'           => TRUE,
                'default'        => 'NULL',
                'after'          => 'otp'
            ), 
        );
        $this->dbforge->add_column('orders', $fields);

        $fields = array(
            'hash_link' => array(
                'type'           => 'VARCHAR',
                'constraint'     => '512',
                'NULL'           => TRUE,
                'default'        => 'NULL',
                'after'          => 'active_status'
            ), 
        );
        $this->dbforge->add_column('order_items', $fields);

        $fields = array(
            'is_sent' => array(
                'type'           => 'TINYINT',
                'constraint'     => '4',
                'NULL'           => TRUE,
                'default'        => '0',
                'after'          => 'hash_link'
            ), 
        );
        $this->dbforge->add_column('order_items', $fields);
    }
    public function down()
    {
        $this->dbforge->drop_table('blogs');
        $this->dbforge->drop_table('blog_categories');
        $this->dbforge->drop_table('digital_orders_mails');
        $this->dbforge->drop_column('products', 'download_allowed');
        $this->dbforge->drop_column('products', 'download_type');
        $this->dbforge->drop_column('products', 'download_link');
        $this->dbforge->drop_column('orders', 'email');
        $this->dbforge->drop_column('order_items', 'hash_link');
        $this->dbforge->drop_column('order_items', 'is_sent');
    }
}
