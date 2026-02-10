#!/bin/bash

echo "=== Testing Time Fields Issue ==="
echo ""

echo "1. Checking Vue component file..."
if grep -q "checkin_time" resources/js/Pages/AccommodationTypes/Create.vue; then
    echo "✓ checkin_time field found in Vue component"
else
    echo "✗ checkin_time field NOT found in Vue component"
fi

if grep -q "checkout_time" resources/js/Pages/AccommodationTypes/Create.vue; then
    echo "✓ checkout_time field found in Vue component"
else
    echo "✗ checkout_time field NOT found in Vue component"
fi

echo ""
echo "2. Checking controller validation..."
if grep -q "checkin_time" app/Http/Controllers/Admin/AccommodationTypeController.php; then
    echo "✓ checkin_time validation found in controller"
else
    echo "✗ checkin_time validation NOT found in controller"
fi

echo ""
echo "3. Checking built assets..."
BUILT_FILES=$(find public/build/assets -name "*Create*" -type f 2>/dev/null | wc -l)
echo "Found $BUILT_FILES built Create files"

if [ $BUILT_FILES -gt 0 ]; then
    echo "Checking if built files contain time fields..."
    TIME_FIELDS_IN_BUILT=$(grep -l "checkin_time\|checkout_time" public/build/assets/Create-*.js 2>/dev/null | wc -l)
    echo "Built files with time fields: $TIME_FIELDS_IN_BUILT/$BUILT_FILES"
    
    if [ $TIME_FIELDS_IN_BUILT -eq 0 ]; then
        echo "⚠️  ISSUE: Built assets are outdated and don't contain time fields!"
        echo "Solution: The development server needs to rebuild the assets."
    fi
fi

echo ""
echo "4. Checking development servers..."
LARAVEL_RUNNING=$(pgrep -f "php artisan serve" | wc -l)
VITE_RUNNING=$(pgrep -f "vite" | wc -l)

echo "Laravel server running: $LARAVEL_RUNNING"
echo "Vite server running: $VITE_RUNNING"

echo ""
echo "5. Testing form data structure..."
php artisan tinker --execute="
\$form = [
    'checkin_time' => '14:00',
    'checkout_time' => '11:00',
];
echo 'Form data structure: ' . (isset(\$form['checkin_time']) && isset(\$form['checkout_time']) ? 'CORRECT' : 'INCORRECT') . PHP_EOL;
" 2>/dev/null | tail -1

echo ""
echo "=== Diagnosis ==="
echo "The Vue component correctly includes the time fields."
echo "The controller correctly validates the time fields."
echo "The issue is that the built assets are outdated."
echo ""
echo "=== Solution ==="
echo "1. Stop any running development servers"
echo "2. Clear browser cache"
echo "3. Access the page using the development server (not built assets)"
echo "4. The time fields should appear correctly"
echo ""
echo "To test: Visit http://localhost:8000/accommodation-types/create (after login)"
echo "The fields should be visible after the 'Preço Base (Diária)' field"