<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Workspace;
use App\Models\Project;
use App\Models\User;

echo "=== Flex Progress Test ===\n";

// Simulate workspace
$ws = new Workspace();
$ws->freelancer_id = 1;
$ws->stages = [
    ['name'=>'Tahap A','description'=>null,'created_by'=>1,'is_completed'=>false,'note'=>null],
    ['name'=>'Tahap B','description'=>null,'created_by'=>1,'is_completed'=>false,'note'=>null],
    ['name'=>'Tahap C','description'=>null,'created_by'=>1,'is_completed'=>false,'note'=>null],
];
echo "Total: ".$ws->totalStages()."\n";
echo "Completed: ".$ws->completedStagesCount()."\n";
echo "Progress: ".$ws->calculateFlexibleProgress()."% (expect 0)\n";

// Toggle Tahap C (non-linear, pilih yang terakhir dulu)
$items = $ws->stageItems();
$items[2]['is_completed'] = true;
$items[2]['note'] = 'Selesai tahap C duluan';
$ws->stages = $items;
echo "After completing C (1/3): ".$ws->calculateFlexibleProgress()."% (expect 33)\n";
echo "currentProgress: ".$ws->currentProgress()."% \n";

// Toggle Tahap A
$items = $ws->stageItems();
$items[0]['is_completed'] = true;
$ws->stages = $items;
echo "After A+C (2/3): ".$ws->calculateFlexibleProgress()."% (expect 67)\n";

// Toggle all
$items = $ws->stageItems();
$items[1]['is_completed'] = true;
$ws->stages = $items;
echo "After all 3 (3/3): ".$ws->calculateFlexibleProgress()."% (expect 100)\n";

// Uncheck B
$items = $ws->stageItems();
$items[1]['is_completed'] = false;
$ws->stages = $items;
echo "After uncheck B (2/3): ".$ws->calculateFlexibleProgress()."% (expect 67)\n";

echo "Stage items dump:\n";
print_r($ws->stageItems());

echo "=== Done ===\n";
