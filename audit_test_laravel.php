<?php
require __DIR__.'/vendor/autoload.php';

use Illuminate\Contracts\Console\Kernel;

$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

echo "=== Laravel Timezone Audit ===\n";
echo "App timezone (config): " . config('app.timezone') . "\n";
echo "PHP date_default_timezone_get: " . date_default_timezone_get() . "\n";

$now = \Carbon\Carbon::now();
echo "Carbon::now(): " . $now . "\n";
echo "Carbon::now() TZ: " . $now->timezoneName . "\n";

$today = \Carbon\Carbon::today();
echo "Carbon::today(): " . $today . "\n";
echo "Carbon::today() date: " . $today->toDateString() . "\n";

echo "\n=== Deadline Parse Test ===\n";
$d = \Carbon\Carbon::parse('2026-08-31');
echo "Parsed '2026-08-31': " . $d . "\n";
echo "Parsed TZ: " . $d->timezoneName . "\n";
echo "endOfDay: " . $d->endOfDay() . "\n";
echo "endOfDay TZ: " . $d->endOfDay()->timezoneName . "\n";
echo "endOfDay timestamp: " . $d->endOfDay()->getTimestamp() . "\n";
echo "endOfDay * 1000 (deadlineMs): " . ($d->endOfDay()->getTimestamp() * 1000) . "\n";

echo "\n=== Diff Calculations (actual current time) ===\n";
$nowTs = \Carbon\Carbon::now()->getTimestamp();
$deadlineTs = $d->endOfDay()->getTimestamp();
echo "Now timestamp (Carbon::now): " . $nowTs . "\n";
echo "Deadline timestamp (endOfDay): " . $deadlineTs . "\n";
echo "Remaining sec: " . ($deadlineTs - $nowTs) . "\n";
echo "Remaining days: " . intdiv($deadlineTs - $nowTs, 86400) . "\n";
echo "Remaining hours: " . intdiv(($deadlineTs - $nowTs) % 86400, 3600) . "\n";

echo "\n=== JS Date.now() ===\n";
echo "Date.now equivalent: " . (int)(microtime(true) * 1000) . "\n";

echo "\n=== MarkOverdueWorkspaces logic simulation ===\n";
echo "Carbon::today() (for overdue check): " . $today->toDateString() . "\n";
echo "Deadline date string: 2026-08-31\n";
echo "Is '2026-08-31' < '" . $today->toDateString() . "'? " . ('2026-08-31' < $today->toDateString() ? 'YES (mark as Melewati Batas Waktu)' : 'NO (not marked)') . "\n";

echo "\n=== Simulated: Laptop = Sep 1 00:00 Asia/Jakarta (UTC+7) ===\n";
$simNow = \Carbon\Carbon::parse('2026-09-01 00:00:00', 'Asia/Jakarta')->setTimezone(config('app.timezone'));
echo "Simulated now in app TZ: " . $simNow . " (TZ: " . $simNow->timezoneName . ")\n";
echo "Simulated now timestamp: " . $simNow->getTimestamp() . "\n";
echo "Carbon::today() would be: " . $simNow->toDateString() . "\n";
echo "Remaining sec: " . ($deadlineTs - $simNow->getTimestamp()) . "\n";
echo "Is deadline < simulated today? " . ('2026-08-31' < $simNow->toDateString() ? 'YES (mark as Melewati Batas Waktu)' : 'NO (not marked)') . "\n";

echo "\n=== Simulated: Laptop = Aug 31 00:00 Asia/Jakarta (UTC+7) ===\n";
$simNow2 = \Carbon\Carbon::parse('2026-08-31 00:00:00', 'Asia/Jakarta')->setTimezone(config('app.timezone'));
echo "Simulated now in app TZ: " . $simNow2 . " (TZ: " . $simNow2->timezoneName . ")\n";
echo "Simulated now timestamp: " . $simNow2->getTimestamp() . "\n";
echo "Carbon::today() would be: " . $simNow2->toDateString() . "\n";
echo "Remaining sec: " . ($deadlineTs - $simNow2->getTimestamp()) . "\n";
echo "Remaining days: " . intdiv($deadlineTs - $simNow2->getTimestamp(), 86400) . "\n";
echo "Is deadline < simulated today? " . ('2026-08-31' < $simNow2->toDateString() ? 'YES (mark as Melewati Batas Waktu)' : 'NO (not marked)') . "\n";

