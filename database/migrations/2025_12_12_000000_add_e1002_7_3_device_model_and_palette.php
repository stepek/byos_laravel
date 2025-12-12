<?php

use App\Models\DeviceModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $currentTime = date('Y-m-d H:i:s');
        
        // Create the new device model
        $deviceModels = [
            [
                'name' => 'E1002_7_3',
                'label' => 'Seeed reTerminal E1002 7.3"',
                'description' => 'Seeed Studio reTerminal E1002 - 7.3" full-color E Ink Spectra 6 display',
                'width' => 800,
                'height' => 480,
                'colors' => 256,
                'bit_depth' => 8,
                'scale_factor' => 1,
                'rotation' => 0,
                'mime_type' => 'image/png',
                'offset_x' => 0,
                'offset_y' => 0,
                'published_at' => '2023-09-14 00:00:00',
                'source' => 'api',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
        ];

        // Upsert the device model
        DB::table('device_models')->upsert(
            $deviceModels,
            ['name'],
            [
                'label', 'description', 'width', 'height', 'colors', 'bit_depth', 'scale_factor',
                'rotation', 'mime_type', 'offset_x', 'offset_y', 'published_at', 'source',
                'created_at', 'updated_at',
            ]
        );

        // Create the new palette
        $palettes = [
            [
                'name' => 'color-e1002-7',
                'description' => 'E Ink Spectra 6 (7 Colors)',
                'grays' => 2,
                'colors' => json_encode([
                    '#000000',  // Black (K - Black)
                    '#FFFFFF',  // White (W - White)
                    '#FF0000',  // Red (R - Red)
                    '#00FF00',  // Green (G - Green)
                    '#0000FF',  // Blue (B - Blue)
                    '#FFFF00',  // Yellow (Y - Yellow)
                    '#FF6600',  // Orange (O - Orange) - native to Spectra 6
                ]),
                'framework_class' => '',
                'source' => 'api',
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ],
        ];

        // Insert the palette using upsert
        DB::table('device_palettes')->upsert(
            $palettes,
            ['name'],
            [
                'description', 'grays', 'colors', 'framework_class', 'source',
                'created_at', 'updated_at',
            ]
        );

        // Get the palette ID and link the device model with the palette
        $palette = DB::table('device_palettes')->where('name', 'color-e1002-7')->first();
        
        if ($palette) {
            DB::table('device_models')->where('name', 'E1002_7_3')->update(['palette_id' => $palette->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the relationship first
        DB::table('device_models')->where('name', 'E1002_7_3')->update(['palette_id' => null]);
        
        // Delete the device model and palette
        DB::table('device_models')->where('name', 'E1002_7_3')->delete();
        DB::table('device_palettes')->where('name', 'color-e1002-7')->delete();
    }
};
