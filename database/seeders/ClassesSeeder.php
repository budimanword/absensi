<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            ['name' => 'Kelas A'],
            ['name' => 'Kelas B'],
            ['name' => 'Kelas C'],
            ['name' => 'Kelas D'],
            ['name' => 'Kelas E'],
        ]);
        
    }
}
