<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if ($user) auth()->login($user);

$patient = App\Models\IpdPatient::first();
if ($patient) {
    $controller = new App\Http\Controllers\IpdPatientController();
    $response = $controller->ipd_patient_detail($patient->id);
    $html = $response->render();
    $lines = explode("\n", $html);
    echo "Total lines: " . count($lines) . "\n";
    for ($i = 2770; $i <= 2820 && $i < count($lines); $i++) {
        echo ($i + 1) . ": " . substr(rtrim($lines[$i]), 0, 120) . "\n";
    }
} else {
    echo "No patient found\n";
}
