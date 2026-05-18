<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Part;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Image::exists()) {
            $this->command->info('Images already seeded, skipping...');
            return;
        }

        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Please run UserSeeder first.');
            return;
        }

        $images = [];

        // Product/Part Images (30 images)
        $productImages = [
            ['file' => 'motor-front-view.jpg', 'title' => 'Motor Front View', 'alt' => 'Electric motor front view', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1080],
            ['file' => 'motor-side-view.jpg', 'title' => 'Motor Side View', 'alt' => 'Electric motor side view', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1080],
            ['file' => 'pump-assembly.png', 'title' => 'Pump Assembly', 'alt' => 'Hydraulic pump assembly view', 'type' => 'image/png', 'width' => 2400, 'height' => 1600],
            ['file' => 'control-panel-closed.jpg', 'title' => 'Control Panel Closed', 'alt' => 'Control panel exterior', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1440],
            ['file' => 'control-panel-open.jpg', 'title' => 'Control Panel Open', 'alt' => 'Control panel interior wiring', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1440],
            ['file' => 'gearbox-cutaway.png', 'title' => 'Gearbox Cutaway', 'alt' => 'Gearbox internal components', 'type' => 'image/png', 'width' => 2000, 'height' => 2000],
            ['file' => 'servo-drive-front.jpg', 'title' => 'Servo Drive Front', 'alt' => 'Servo drive control panel', 'type' => 'image/jpeg', 'width' => 1600, 'height' => 1200],
            ['file' => 'actuator-extended.jpg', 'title' => 'Actuator Extended', 'alt' => 'Linear actuator fully extended', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1080],
            ['file' => 'sensor-mounted.png', 'title' => 'Sensor Mounted', 'alt' => 'Pressure sensor installation', 'type' => 'image/png', 'width' => 1600, 'height' => 1600],
            ['file' => 'controller-display.jpg', 'title' => 'Controller Display', 'alt' => 'Temperature controller LCD', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1080],
            ['file' => 'cylinder-detail.png', 'title' => 'Cylinder Detail', 'alt' => 'Pneumatic cylinder close-up', 'type' => 'image/png', 'width' => 2400, 'height' => 1800],
            ['file' => 'relay-contacts.jpg', 'title' => 'Relay Contacts', 'alt' => 'Safety relay contact arrangement', 'type' => 'image/jpeg', 'width' => 1600, 'height' => 1200],
            ['file' => 'inverter-connections.jpg', 'title' => 'Inverter Connections', 'alt' => 'Frequency inverter terminal block', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1440],
            ['file' => 'encoder-shaft.png', 'title' => 'Encoder Shaft', 'alt' => 'Rotary encoder shaft coupling', 'type' => 'image/png', 'width' => 1800, 'height' => 1800],
            ['file' => 'breaker-panel.jpg', 'title' => 'Breaker Panel', 'alt' => 'Circuit breaker in enclosure', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1080],
        ];

        foreach ($productImages as $index => $img) {
            $images[] = $this->createImageData(
                fileName: $img['file'],
                title: $img['title'],
                altText: $img['alt'],
                mimeType: $img['type'],
                width: $img['width'],
                height: $img['height'],
                fileSize: rand(200000, 2000000),
                users: $users
            );
        }

        // Technical Drawings (15 PDFs)
        $technicalDrawings = [
            ['file' => 'motor-schematic-3kw.pdf', 'title' => 'Motor Electrical Schematic', 'alt' => 'Electrical wiring diagram for 3kW motor'],
            ['file' => 'pump-assembly-dwg.pdf', 'title' => 'Pump Assembly Drawing', 'alt' => 'Exploded view assembly drawing'],
            ['file' => 'panel-layout-cp1000.pdf', 'title' => 'Control Panel Layout', 'alt' => 'Panel component layout diagram'],
            ['file' => 'gearbox-dimensions.pdf', 'title' => 'Gearbox Dimensional Drawing', 'alt' => 'Gearbox mounting dimensions'],
            ['file' => 'drive-wiring-diagram.pdf', 'title' => 'Servo Drive Wiring', 'alt' => 'Servo drive connection diagram'],
            ['file' => 'actuator-installation.pdf', 'title' => 'Actuator Installation Guide', 'alt' => 'Linear actuator mounting instructions'],
            ['file' => 'sensor-datasheet.pdf', 'title' => 'Sensor Technical Datasheet', 'alt' => 'Pressure sensor specifications'],
            ['file' => 'controller-manual.pdf', 'title' => 'Controller User Manual', 'alt' => 'Temperature controller programming guide'],
            ['file' => 'cylinder-spec.pdf', 'title' => 'Cylinder Specification', 'alt' => 'Pneumatic cylinder technical specs'],
            ['file' => 'relay-schematic.pdf', 'title' => 'Relay Circuit Schematic', 'alt' => 'Safety relay wiring schematic'],
            ['file' => 'inverter-parameters.pdf', 'title' => 'Inverter Parameter List', 'alt' => 'Frequency inverter configuration'],
            ['file' => 'encoder-mounting.pdf', 'title' => 'Encoder Mounting Drawing', 'alt' => 'Encoder installation dimensions'],
            ['file' => 'breaker-curve.pdf', 'title' => 'Breaker Trip Curve', 'alt' => 'Circuit breaker characteristic curve'],
            ['file' => 'bearing-spec.pdf', 'title' => 'Bearing Specification', 'alt' => 'Ball bearing technical data'],
            ['file' => 'belt-selection.pdf', 'title' => 'Belt Selection Guide', 'alt' => 'Timing belt sizing chart'],
        ];

        foreach ($technicalDrawings as $index => $drawing) {
            $images[] = $this->createImageData(
                fileName: $drawing['file'],
                title: $drawing['title'],
                altText: $drawing['alt'],
                mimeType: 'application/pdf',
                width: null,
                height: null,
                fileSize: rand(500000, 3000000),
                users: $users,
                disk: 'public',
                pathPrefix: 'drawings'
            );
        }

        // Material/Component Photos (10 images)
        $materialImages = [
            ['file' => 'steel-sheet-stack.jpg', 'title' => 'Steel Sheet Stock', 'alt' => 'Stack of steel sheets', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1280],
            ['file' => 'aluminium-bar-bundle.jpg', 'title' => 'Aluminium Bar Bundle', 'alt' => 'Bundle of aluminium bars', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1280],
            ['file' => 'copper-wire-spool.jpg', 'title' => 'Copper Wire Spool', 'alt' => 'Industrial copper wire spool', 'type' => 'image/jpeg', 'width' => 1600, 'height' => 1600],
            ['file' => 'plastic-pellets.png', 'title' => 'HDPE Plastic Pellets', 'alt' => 'Container of HDPE pellets', 'type' => 'image/png', 'width' => 1800, 'height' => 1200],
            ['file' => 'brass-rod-section.jpg', 'title' => 'Brass Rod Cross-Section', 'alt' => 'Cut brass rod showing cross-section', 'type' => 'image/jpeg', 'width' => 2000, 'height' => 1500],
            ['file' => 'timber-planks.jpg', 'title' => 'Oak Timber Planks', 'alt' => 'Stack of oak planks', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1280],
            ['file' => 'stainless-steel-finish.jpg', 'title' => 'Stainless Steel Surface', 'alt' => '304 stainless steel finish', 'type' => 'image/jpeg', 'width' => 2400, 'height' => 1600],
            ['file' => 'pvc-pipe-lengths.jpg', 'title' => 'PVC Pipe Inventory', 'alt' => 'PVC pipes various lengths', 'type' => 'image/jpeg', 'width' => 1920, 'height' => 1080],
            ['file' => 'carbon-fibre-weave.png', 'title' => 'Carbon Fibre Weave Pattern', 'alt' => 'Carbon fibre material close-up', 'type' => 'image/png', 'width' => 2400, 'height' => 2400],
            ['file' => 'rubber-gasket-roll.jpg', 'title' => 'Rubber Gasket Material Roll', 'alt' => 'Roll of gasket material', 'type' => 'image/jpeg', 'width' => 1600, 'height' => 1200],
        ];

        foreach ($materialImages as $index => $img) {
            $images[] = $this->createImageData(
                fileName: $img['file'],
                title: $img['title'],
                altText: $img['alt'],
                mimeType: $img['type'],
                width: $img['width'],
                height: $img['height'],
                fileSize: rand(300000, 2500000),
                users: $users
            );
        }

        $created = 0;

        foreach ($images as $imageData) {
            $image = Image::firstOrCreate(
                ['file_path' => $imageData['file_path']],
                $imageData
            );

            if ($image->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->command->info("Created {$created} images.");

        // Attach images to parts
        $this->attachImagesToParts();
    }

    /**
     * Create image data array.
     */
    private function createImageData(
        string $fileName,
        string $title,
        string $altText,
        string $mimeType,
        ?int $width,
        ?int $height,
        int $fileSize,
        $users,
        string $disk = 'public',
        string $pathPrefix = 'images'
    ): array {
        $yearMonth = date('Y/m');
        $filePath = "{$pathPrefix}/{$yearMonth}/{$fileName}";

        return [
            'file_name' => $fileName,
            'file_path' => $filePath,
            'disk' => $disk,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'width' => $width,
            'height' => $height,
            'alt_text' => $altText,
            'title' => $title,
            'description' => $title . ' - High resolution image for documentation and reference.',
            'uploaded_by' => $users->random()->id,
        ];
    }

    /**
     * Attach images to parts with realistic relationships.
     */
    private function attachImagesToParts(): void
    {
        $parts = Part::limit(50)->get();
        $images = Image::all();

        if ($parts->isEmpty() || $images->isEmpty()) {
            $this->command->warn('No parts or images found to create relationships.');
            return;
        }

        $attached = 0;

        foreach ($parts as $part) {
            // Each part gets 1-4 images
            $numImages = rand(1, 4);
            $partImages = $images->random(min($numImages, $images->count()));

            foreach ($partImages as $index => $image) {
                $part->images()->attach($image->id, [
                    'sort_order' => $index,
                    'is_primary' => $index === 0, // First image is primary
                    'usage_context' => match ($index) {
                        0 => 'thumbnail',
                        1 => 'gallery',
                        default => rand(0, 1) ? 'technical_drawing' : 'gallery',
                    },
                ]);
                $attached++;
            }
        }

        $this->command->info("Attached {$attached} image relationships to parts.");
    }
}
