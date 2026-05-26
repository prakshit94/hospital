<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HealthCheckup;
use App\Models\Employee;

$record = new HealthCheckup();
$record->full_name = 'Test Name';

echo "Full Name Attribute: " . $record->full_name . "\n";
echo "Attributes Array: ";
print_r($record->getAttributes());
