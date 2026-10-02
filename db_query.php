<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== App Timezone ===\n";
echo "config app.timezone: " . config('app.timezone') . "\n";
echo "date_default_timezone_get: " . date_default_timezone_get() . "\n";

echo "\n=== Projects with Deadline ===\n";
$projects = DB::table('projects')->whereNotNull('deadline')->orderBy('id', 'desc')->limit(10)->get();
foreach ($projects as $p) {
    echo "ID={$p->id} | Name={$p->project_name} | Deadline={$p->deadline} | Status={$p->status}\n";
}

echo "\n=== Workspaces ===\n";
$ws = DB::table('project_workspaces')->orderBy('id', 'desc')->limit(20)->get();
foreach ($ws as $w) {
    echo "ID={$w->id} | ProjectID={$w->project_id} | CompanyID={$w->company_id} | FreelancerID={$w->freelancer_id} | Status={$w->status}\n";
}

echo "\n=== Deadline Type Test ===\n";
if (count($projects) > 0) {
    $first = $projects[0];
    echo "Raw deadline value: '{$first->deadline}'\n";
    $c = \Carbon\Carbon::parse($first->deadline);
    echo "Carbon parse: " . $c . "\n";
    echo "Carbon parse TZ: " . $c->timezoneName . "\n";
    echo "endOfDay: " . $c->endOfDay() . "\n";
    echo "endOfDay TZ: " . $c->endOfDay()->timezoneName . "\n";
    echo "endOfDay ts: " . $c->endOfDay()->getTimestamp() . "\n";
    echo "endOfDay * 1000: " . ($c->endOfDay()->getTimestamp() * 1000) . "\n";
    echo "format d M Y: " . $c->format('d M Y') . "\n";
    echo "now: " . \Carbon\Carbon::now() . "\n";
    echo "remaining sec: " . ($c->endOfDay()->getTimestamp() - \Carbon\Carbon::now()->getTimestamp()) . "\n";
    echo "remaining days: " . intdiv($c->endOfDay()->getTimestamp() - \Carbon\Carbon::now()->getTimestamp(), 86400) . "\n";
}
