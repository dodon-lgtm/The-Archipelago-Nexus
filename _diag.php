<?php
$root = 'C:/xampp/htdocs/The-Archipelago-Nexus';

echo "===== LOGIN 388-432 =====\n";
$a = file($root . '/resources/views/auth/login.blade.php');
for ($i = 387; $i < 432 && $i < count($a); $i++) {
    echo ($i + 1) . ': ' . $a[$i];
}

echo "\n===== LANDINGPAGE =====\n";
$lp = $root . '/resources/views/landingpage.blade.php';
$lpLines = file($lp);
echo "(total lines = " . count($lpLines) . ")\n";
foreach ($lpLines as $n => $line) {
    $ln = $n + 1;
    if (preg_match('/footer|Footer|Copyright|copyright|<footer|<\/body>|@include|bg-slate-900|site-footer/i', $line)) {
        echo $ln . ': ' . $line;
    }
}

echo "\n===== ADMIN NAV 246-300 =====\n";
$b = file($root . '/resources/views/layouts/admin.blade.php');
for ($i = 245; $i < 300 && $i < count($b); $i++) {
    echo ($i + 1) . ': ' . $b[$i];
}

echo "\n===== ADMIN </nav> + settings labels =====\n";
foreach ($b as $n => $line) {
    $ln = $n + 1;
    if (preg_match('/<\/nav>|Pengaturan|Password|password|Kebij|pengguna|withdraw|payment|Logout|Kelola|sidebar-toggle/i', $line)) {
        echo $ln . ': ' . $line;
    }
}
