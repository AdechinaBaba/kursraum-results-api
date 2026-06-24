<?php

namespace Database\Seeders;

use App\Models\Center;
use Illuminate\Database\Seeder;

class CenterSeeder extends Seeder
{
    public function run(): void
    {
        Center::create([
            'name' => 'KURSRAUM',
            'slug'=> 'Womey', 
            'address' => 'Benin-Cotonou',
            'phone' => '+22998270177',
            'status' => true
        ]);
    }
}