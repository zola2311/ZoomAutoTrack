<?php

namespace Database\Seeders;

use App\Models\InspectionItem;
use Illuminate\Database\Seeder;

class InspectionItemsSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            // Engine
            ['name' => 'Engine Oil Level', 'category' => 'engine', 'input_type' => 'pass_fail_warning'],
            ['name' => 'Engine Oil Condition', 'category' => 'engine', 'input_type' => 'pass_fail', 'requires_photo' => true],
            ['name' => 'Engine Coolant Level', 'category' => 'engine', 'input_type' => 'pass_fail_warning'],
            ['name' => 'Engine Belts Condition', 'category' => 'engine', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],

            // Brakes
            ['name' => 'Brake Pad Thickness (mm)', 'category' => 'brakes', 'input_type' => 'numeric', 'requires_photo' => true],
            ['name' => 'Brake Disc Condition', 'category' => 'brakes', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
            ['name' => 'Brake Fluid Level', 'category' => 'brakes', 'input_type' => 'pass_fail_warning'],
            ['name' => 'Handbrake Function', 'category' => 'brakes', 'input_type' => 'pass_fail'],

            // Tires
            ['name' => 'Tire Tread Depth (mm)', 'category' => 'tires', 'input_type' => 'numeric', 'requires_photo' => true],
            ['name' => 'Tire Pressure (PSI)', 'category' => 'tires', 'input_type' => 'numeric'],
            ['name' => 'Tire Condition', 'category' => 'tires', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
            ['name' => 'Spare Tire Condition', 'category' => 'tires', 'input_type' => 'pass_fail'],

            // Electrical
            ['name' => 'Battery Voltage', 'category' => 'battery', 'input_type' => 'numeric'],
            ['name' => 'Battery Terminals Condition', 'category' => 'battery', 'input_type' => 'pass_fail', 'requires_photo' => true],
            ['name' => 'Headlights Function', 'category' => 'lighting', 'input_type' => 'pass_fail'],
            ['name' => 'Brake Lights Function', 'category' => 'lighting', 'input_type' => 'pass_fail'],
            ['name' => 'Turn Signals Function', 'category' => 'lighting', 'input_type' => 'pass_fail'],

            // Fluids
            ['name' => 'Transmission Fluid Level', 'category' => 'fluids', 'input_type' => 'pass_fail_warning'],
            ['name' => 'Power Steering Fluid Level', 'category' => 'fluids', 'input_type' => 'pass_fail_warning'],
            ['name' => 'Brake Fluid Condition', 'category' => 'fluids', 'input_type' => 'pass_fail', 'requires_photo' => true],

            // Body & Interior
            ['name' => 'Wiper Blades Condition', 'category' => 'exterior', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
            ['name' => 'Exterior Body Condition', 'category' => 'exterior', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
            ['name' => 'Interior Condition', 'category' => 'interior', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
            ['name' => 'AC System Function', 'category' => 'cooling', 'input_type' => 'pass_fail'],
            ['name' => 'Heater Function', 'category' => 'cooling', 'input_type' => 'pass_fail'],

            // Steering & Suspension
            ['name' => 'Steering Responsiveness', 'category' => 'steering', 'input_type' => 'pass_fail_warning'],
            ['name' => 'Suspension Condition', 'category' => 'suspension', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
            ['name' => 'Shock Absorbers Condition', 'category' => 'suspension', 'input_type' => 'pass_fail_warning', 'requires_photo' => true],
        ];

        foreach ($items as $index => $item) {
            InspectionItem::create(array_merge($item, [
                'sort_order' => $index,
                'is_active' => true,
            ]));
        }
    }
}
