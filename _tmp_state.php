<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$policies = App\Models\Policy::orderBy('id')->get();
foreach ($policies as $p) {
    echo $p->id . ' | key=' . $p->key . ' | title=' . $p->title
        . ' | is_active=' . var_export($p->is_active, true)
        . ' | content_len=' . strlen((string) $p->content)
        . ' | updated=' . ($p->updated_at ? $p->updated_at->toDateTimeString() : '-') . PHP_EOL;
}
echo '--- routes ---' . PHP_EOL;
echo 'policies.index  => ' . (Route::getRoutes()->getByName('admin.policies.index') ? 'OK' : 'MISSING') . PHP_EOL;
echo 'policies.update => ' . (Route::getRoutes()->getByName('admin.policies.update') ? 'OK' : 'MISSING') . PHP_EOL;
echo 'policies.edit   => ' . (Route::getRoutes()->getByName('admin.policies.edit') ? 'OK' : 'MISSING') . PHP_EOL;
