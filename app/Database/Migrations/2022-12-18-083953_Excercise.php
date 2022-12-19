<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Excescise extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'excercise_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'type' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'datetime' => [
                'type'       => 'DATETIME',
                'default'    => null,
            ],
            'is_done' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
            ],
        ]);

        $this->forge->addKey('excercise_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'user_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('excercises');
    }

    public function down()
    {
        $this->forge->dropTable('excercises');
    }
}
