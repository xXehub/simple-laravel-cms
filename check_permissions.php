<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$permissions = Spatie\Permission\Models\Permission::all(['name', 'group'])->toArray();
echo json_encode($permissions, JSON_PRETTY_PRINT);
