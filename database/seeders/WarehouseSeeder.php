<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Warehouse;
use App\Models\Line;
use App\Models\Rack;
use App\Models\Pallet;
use App\Models\Package;
use App\Models\QualityMark;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create quality marks
        $qualityMarks = [
            ['name' => 'Grade A', 'description' => 'Highest quality'],
            ['name' => 'Grade B', 'description' => 'Standard quality'],
            ['name' => 'Grade C', 'description' => 'Below standard']
        ];

        foreach ($qualityMarks as $mark) {
            QualityMark::create($mark);
        }

        // Create warehouses
        $warehouses = [
            [
                'name' => 'Main Warehouse',
                'max_capacity' => 10000.00,
                'lines' => [
                    [
                        'name' => 'Line A',
                        'type' => 'carton',
                        'max_allowed_capacity' => 3000.00,
                        'racks' => 3
                    ],
                    [
                        'name' => 'Line B',
                        'type' => 'mixed',
                        'max_allowed_capacity' => 4000.00,
                        'racks' => 4
                    ]
                ]
            ],
            [
                'name' => 'Secondary Warehouse',
                'max_capacity' => 8000.00,
                'lines' => [
                    [
                        'name' => 'Line C',
                        'type' => 'loose',
                        'max_allowed_capacity' => 2500.00,
                        'racks' => 2
                    ]
                ]
            ]
        ];

        foreach ($warehouses as $warehouseData) {
            $warehouse = Warehouse::create([
                'name' => $warehouseData['name'],
                'max_capacity' => $warehouseData['max_capacity']
            ]);

            foreach ($warehouseData['lines'] as $lineData) {
                $line = Line::create([
                    'warehouse_id' => $warehouse->id,
                    'name' => $lineData['name'],
                    'type' => $lineData['type'],
                    'max_allowed_capacity' => $lineData['max_allowed_capacity']
                ]);

                // Create racks for each line
                for ($i = 1; $i <= $lineData['racks']; $i++) {
                    $rack = Rack::create([
                        'line_id' => $line->id,
                        'serial_number' => "RACK-{$line->name}-{$i}",
                        'capacity' => 1000.00
                    ]);

                    // Create 2 pallets for each rack
                    for ($j = 1; $j <= 2; $j++) {
                        $pallet = Pallet::create([
                            'rack_id' => $rack->id,
                            'serial_number' => "PLT-{$rack->serial_number}-{$j}",
                            'max_weight' => 500.00
                        ]);

                        // Create some packages for each pallet
                        for ($k = 1; $k <= 2; $k++) {
                            Package::create([
                                'serial_number' => "PKG-{$pallet->serial_number}-{$k}",
                                'type' => $lineData['type'] === 'mixed' ? ($k % 2 === 0 ? 'carton' : 'loose') : ($lineData['type'] === 'loose' ? 'loose' : 'carton'),
                                'mass' => rand(50, 150) / 2,
                                'barcode' => "BC" . str_pad(rand(1000, 9999), 8, '0', STR_PAD_LEFT),
                                'pallet_id' => $pallet->id,
                                'quality_mark_id' => rand(1, 3),
                                'is_discarded' => rand(1, 10) === 1
                            ]);
                        }
                    }
                }
            }
        }
    }
}
