<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class PublicSiteSeeder extends Seeder
{
    /**
     * Seeds the eight starter services so the public site isn't empty.
     * Uses updateOrCreate on slug, so re-running won't duplicate rows
     * or overwrite edits you've made in the admin panel to other fields.
     *
     * Team members and gallery images are deliberately NOT seeded —
     * those need your real staff and your real workshop photos, added
     * through the admin panel.
     */
    public function run(): void
    {
        $services = [
            [
                'slug' => 'basic-service',
                'title' => 'Basic / Regular Service',
                'booking_key' => 'basic_service',
                'overview' => 'Routine servicing keeps small problems small. A basic service covers the checks and replacements your vehicle needs at regular intervals — fluids, filters, brakes, tyres, lights and battery — and every item we check is logged to your vehicle\'s digital record, so you always know what was done and when.',
                'points' => [
                    'Recommended at regular mileage or time intervals',
                    'Full multi-point inspection, logged item by item',
                    'We flag anything that needs attention before it becomes urgent',
                    'Same day service for most routine work',
                ],
            ],
            [
                'slug' => 'engine-diagnostics',
                'title' => 'Engine Diagnostics',
                'booking_key' => 'engine_diagnostics',
                'overview' => 'A warning light on the dash tells you something is wrong, but not what. Our diagnostic equipment reads the fault codes your vehicle\'s computer is storing, so we can identify the actual problem rather than replacing parts and hoping. The diagnosis and the codes we found are recorded on your job card.',
                'points' => [
                    'Fault codes read and explained in plain language',
                    'We diagnose before we quote — no guesswork repairs',
                    'Findings recorded to your vehicle\'s service history',
                    'Handles both foreign and domestic vehicles',
                ],
            ],
            [
                'slug' => 'lube-oil-filters',
                'title' => 'Lube, Oil and Filters',
                'booking_key' => 'oil_change',
                'overview' => 'Clean oil and a clean filter are the cheapest insurance an engine has. We drain, replace and top up to your manufacturer\'s specification, and log the mileage — so your next change is calculated from a real number, not a guess.',
                'points' => [
                    'Oil and filter replaced to manufacturer specification',
                    'Mileage recorded, so your next service date is accurate',
                    'Air and cabin filters checked at the same time',
                    'Quick turnaround — usually same day',
                ],
            ],
            [
                'slug' => 'belts-hoses',
                'title' => 'Belts and Hoses',
                'booking_key' => 'belts_hoses',
                'overview' => 'Belts and hoses fail gradually, then suddenly. A cracked belt or a perished hose can leave you stranded or overheat an engine in minutes. We inspect for wear, cracking and tension, and replace before failure rather than after.',
                'points' => [
                    'Drive belts, timing belts and coolant hoses inspected',
                    'Replacement before failure, not after a breakdown',
                    'Tension and alignment checked on refit',
                    'Condition noted in your service history each visit',
                ],
            ],
            [
                'slug' => 'air-conditioning',
                'title' => 'Air Conditioning',
                'booking_key' => 'air_conditioning',
                'overview' => 'If your A/C is blowing warm, the cause is usually low refrigerant, a leak, or a failing compressor. We test the system under pressure to find which, recharge to specification, and tell you honestly if a recharge alone won\'t hold.',
                'points' => [
                    'System pressure-tested to locate leaks',
                    'Recharged to manufacturer specification',
                    'Compressor and condenser condition assessed',
                    'Cabin filter checked — often the real cause of poor airflow',
                ],
            ],
            [
                'slug' => 'brake-repair',
                'title' => 'Brake Repair',
                'booking_key' => 'brake_repair',
                'overview' => 'Brakes are the one system worth never postponing. We measure pad and disc wear rather than estimating it, so you get a straight answer on whether you need replacement now or can safely wait — and the measurements go on your record either way.',
                'points' => [
                    'Pad and disc thickness measured, not estimated',
                    'Front and rear systems inspected together',
                    'Brake fluid condition checked',
                    'Measurements logged so wear can be tracked over time',
                ],
            ],
            [
                'slug' => 'tire-wheel',
                'title' => 'Tire and Wheel Services',
                'booking_key' => 'tire_wheel',
                'overview' => 'Uneven tyre wear is usually a symptom of something else — alignment, suspension or pressure. We treat the cause, not just the tyre, so your next set lasts as long as it should.',
                'points' => [
                    'Wheel alignment and balancing',
                    'Tyre rotation and pressure checks',
                    'Wear pattern assessed to identify underlying causes',
                    'Puncture repair where the tyre is safely repairable',
                ],
            ],
            [
                'slug' => 'ev-service',
                'title' => 'EV Battery & Charging',
                'booking_key' => 'ev_service',
                'overview' => 'Electric vehicles need less routine maintenance, but what they do need is specific. We check battery state of health, charging system performance, and the systems EVs still share with combustion vehicles — brakes, tyres, suspension and cabin climate.',
                'points' => [
                    'Battery state of health assessment',
                    'Charging system and port inspection',
                    'Brake, tyre and suspension checks — still needed on an EV',
                    'Battery health recorded each visit so degradation is visible over time',
                ],
            ],
        ];

        foreach ($services as $index => $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                array_merge($service, [
                    'sort_order' => $index,
                    'is_active' => true,
                ]),
            );
        }
    }
}
