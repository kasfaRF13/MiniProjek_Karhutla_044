<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['name' => 'Kawasan Hutan Lembang', 'description' => 'Wilayah rawan titik api sektor utara Bandung.'],
            ['name' => 'Kawasan Hutan Dago Pakar', 'description' => 'Zona pengawasan vegetasi kering dan rawan gesekan.'],
            ['name' => 'Kawasan Hutan Cimenyan', 'description' => 'Area perbukitan dengan akses pos pantau terbatas.'],
            ['name' => 'Kawasan Hutan Pangalengan', 'description' => 'Sektor perkebunan dan hutan lindung selatan.'],
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
