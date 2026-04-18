#!/bin/bash

# School Registration - Database & Code Verification Checklist

echo "=== VERIFICATION CHECKLIST ==="
echo ""

echo "1. Running Migration..."
php artisan migrate

echo ""
echo "2. Verifying Table Structure..."
php artisan tinker --execute="
\$columns = DB::getSchemaBuilder()->getColumns('schools');
collect(\$columns)->filter(fn(\$col) => in_array(\$col['name'], ['school_type', 'management_type', 'affiliation_board', 'affiliation_status', 'subscription_plan', 'classes_available', 'streams_available', 'medium_of_instruction']))->each(fn(\$col) => dump(\$col['name'] => \$col['type']));
"

echo ""
echo "3. Checking Master Tables..."
php artisan tinker --execute="
dump('SCHOOL_TYPE ids:', \App\Models\Master::getByGroup('SCHOOL_TYPE')->pluck('m_id')->toArray());
dump('MANAGEMENT_TYPE ids:', \App\Models\Master::getByGroup('MANAGEMENT_TYPE')->pluck('m_id')->toArray());
dump('AFFILIATION_BOARD ids:', \App\Models\Master::getByGroup('AFFILIATION_BOARD')->pluck('m_id')->toArray());
dump('AFFILIATION_STATUS ids:', \App\Models\Master::getByGroup('AFFILIATION_STATUS')->pluck('m_id')->toArray());
dump('SUBSCRIPTION_PLAN ids:', \App\Models\Master::getByGroup('SUBSCRIPTION_PLAN')->pluck('m_id')->toArray());
"

echo ""
echo "=== All checks completed! ==="
echo ""
echo "If you see integer values above, everything is configured correctly."
echo "Now test the registration form in your React app."
