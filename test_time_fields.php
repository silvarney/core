<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create a request to the create page
$request = Illuminate\Http\Request::create('/accommodation-types/create', 'GET');

// Get the user
$user = \App\Models\User::find(1);

// Set the user as authenticated
$app->instance('auth', \Illuminate\Auth\AuthManager::class);
auth()->login($user);

// Get the controller
$controller = new \App\Http\Controllers\Admin\AccommodationTypeController;

// Call the create method
$response = $controller->create();

// Check the response
echo 'Response type: '.get_class($response).PHP_EOL;
echo 'Properties count: '.count($response->getProperties()['properties']).PHP_EOL;

// Check if the view name is correct
echo 'Component: '.$response->component.PHP_EOL;

// Test the form data
$formData = [
    'property_id' => 1,
    'name' => 'Test Suite',
    'description' => 'Test description',
    'capacity_adults' => 2,
    'capacity_children' => 0,
    'size_m2' => 25,
    'base_price' => 150.00,
    'checkin_time' => '14:00',
    'checkout_time' => '11:00',
];

echo 'Form data includes time fields: '.(isset($formData['checkin_time']) && isset($formData['checkout_time']) ? 'YES' : 'NO').PHP_EOL;

// Test validation
$request = Illuminate\Http\Request::create('/accommodation-types/store', 'POST', $formData);
$validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
    'checkin_time' => 'required|date_format:H:i',
    'checkout_time' => 'required|date_format:H:i',
]);

echo 'Time fields validation: '.($validator->passes() ? 'PASS' : 'FAIL').PHP_EOL;

echo 'Test completed successfully!'.PHP_EOL;
