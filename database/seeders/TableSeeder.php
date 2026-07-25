<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Table;
use Illuminate\Support\Str;


class TableSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat contoh Meja 01 dan Meja 02 beserta token QR uniknya
        Table::create([
            'table_number' => 'Meja 01',
            'qr_token' => 'meja-01-' . Str::random(5),
            'is_active' => true,
        ]);

        Table::create([
            'table_number' => 'Meja 02',
            'qr_token' => 'meja-02-' . Str::random(5),
            'is_active' => true,
        ]);
    }
}
