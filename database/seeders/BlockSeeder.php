<?php

namespace Database\Seeders;

use App\Models\Block;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = [
            [1, 'Nueve De Febrero 1', 'Shaw Mall / Martinez'],
            [2, 'Nueve De Febrero 2', 'PHES Area'],
            [3, 'Nueve De Febrero 3', 'Ice Plant to C. Fernando'],
            [4, 'Nueve De Febrero 4', 'C. Fernando to VM Town House'],
            [5, 'Nueve De Febrero 5', 'Triangle Area'],
            [6, 'Prince Ville Area', 'Prince Ville'],
            [7, 'R. Pascual 1', 'R. Pascual Area 1'],
            [8, 'Phoenix Compound', 'Phoenix Compound'],
            [9, 'VM Townhouse', 'VM Townhouse'],
            [10, 'C. Fernando', 'C. Fernando Area'],
            [11, 'F.T. Evangelista', 'F.T. Evangelista'],
            [12, 'V. Victorino', 'V. Victorino'],
            [13, 'Central Area', 'Central Area'],
            [14, 'GMRC', 'GMRC'],
            [15, 'R. Pascual 2', 'R. Pascual Area 2'],
        ];

        foreach ($blocks as [$num, $name, $area]) {
            Block::firstOrCreate(
                ['block_number' => $num],
                ['name' => $name, 'area_description' => $area]
            );
        }
    }
}