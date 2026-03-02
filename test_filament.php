<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $panel = app('filament')->getPanel('admin');
    $resources = $panel->getResources();
    echo "Success! Found " . count($resources) . " resources.\n";
    foreach ($resources as $res) {
        echo "- $res\n";
    }
} catch (\Throwable $e) {
    echo "ERROR CAUGHT:\n";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . " on line " . $e->getLine() . "\n";
}
