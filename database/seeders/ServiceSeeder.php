<?php
namespace Database\Seeders;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Cuci Reguler',    'unit' => 'kg',  'price_per_unit' => 7000,  'description' => 'Cuci + kering, selesai 2-3 hari'],
            ['name' => 'Cuci Express',    'unit' => 'kg',  'price_per_unit' => 12000, 'description' => 'Cuci + kering, selesai hari ini'],
            ['name' => 'Cuci Setrika',    'unit' => 'kg',  'price_per_unit' => 10000, 'description' => 'Cuci + kering + setrika'],
            ['name' => 'Setrika Saja',    'unit' => 'kg',  'price_per_unit' => 5000,  'description' => 'Hanya layanan setrika'],
            ['name' => 'Cuci Sepatu',     'unit' => 'pcs', 'price_per_unit' => 25000, 'description' => 'Cuci sepatu bersih & wangi'],
            ['name' => 'Cuci Tas/Ransel', 'unit' => 'pcs', 'price_per_unit' => 35000, 'description' => 'Cuci tas & ransel'],
            ['name' => 'Cuci Bedcover',   'unit' => 'pcs', 'price_per_unit' => 20000, 'description' => 'Cuci bedcover/selimut besar'],
        ];

        foreach ($services as $s) {
            Service::create(array_merge($s, ['is_active' => true]));
        }
    }
}