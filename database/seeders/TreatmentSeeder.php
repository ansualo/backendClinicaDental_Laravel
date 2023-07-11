<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('treatments')->insert([
            ['name' => 'Consulta general', 'price' => 25],
            ['name' => 'Higiene bucodental', 'price' => 40],
            ['name' => 'Empastes y reconstrucciones', 'price' => 50],
            ['name' => 'Blanqueamiento dental', 'price' => 140],
            ['name' => 'Carillas dentales', 'price' => 250],
            ['name' => 'Odontopediatría', 'price' => 50],
            ['name' => 'Ortodoncia', 'price' => 2500],
            ['name' => 'Ortodoncia estética - Invisalign', 'price' => 4000],
            ['name' => 'Implantes y prótesis', 'price' => 400],
            ['name' => 'Periodoncia', 'price' => 100],
            ['name' => 'Endodoncia', 'price' => 150]
        ]);
    }
}
