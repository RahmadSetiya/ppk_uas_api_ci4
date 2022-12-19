<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Stat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'stat_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'excercise_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
            ],
            'set' => [
                'type'       => 'INT',
                'constraint' => '255',
            ],
            'reps' => [
                'type'       => 'INT',
                'constraint' => '255',
            ],
            'duration' => [
                'type'       => 'INT',
                'constraint' => '255',
            ],
            'weight' => [
                'type'       => 'INT',
                'constraint' => '255',
            ],
        ]);

        $this->forge->addKey('stat_id', true);
        $this->forge->createTable('stats');
    }

    public function down()
    {
        $this->forge->dropTable('stats');
    }
}
