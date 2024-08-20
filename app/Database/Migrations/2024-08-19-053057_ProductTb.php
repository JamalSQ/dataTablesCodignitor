<?php 
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductTb extends Migration
{
    public function up()
    {
        // Code to create the table
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'p_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'p_price'        => [
                'type'       => 'FLOAT',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('products');
    }

    public function down()
    {
        // Code to revert the migration
        $this->forge->dropTable('products');
    }
}
