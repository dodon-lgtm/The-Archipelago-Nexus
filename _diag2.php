<?php
$root = 'C:/xampp/htdocs/The-Archipelago-Nexus';

$lp = $root . '/resources/views/landingpage.blade.php';
$a = file($lp);
echo "===== LANDINGPAGE footer 1033-1052 =====\n";
for ($i = 1032; $i < 1052 && $i < count($a); $i++) {
    echo ($i + 1) . ': ' . $a[$i];
}

echo "\n===== LOGIN checkbox region 378-400 (before modal) =====\n";
$b = file($root . '/resources/views/auth/login.blade.php');
for ($i = 377; $i < 400 && $i < count($b); $i++) {
    echo ($i + 1) . ': ' . $b[$i];
}

echo "\n===== ADMIN Pengaturan block 278-306 =====\n";
$c = file($root . '/resources/views/layouts/admin.blade.php');
for ($i = 277; $i < 306 && $i < count($c); $i++) {
    echo ($i + 1) . ': ' . $c[$i];
}

echo "\n===== web.php 540-572 (admin group open + policies) =====\n";
$w = file($root . '/routes/web.php');
for ($i = 539; $i < 572 && $i < count($w); $i++) {
    echo ($i + 1) . ': ' . $w[$i];
}

echo "\n===== LANDINGPAGE footer-ish matches =====\n";
foreach ($a as $n => $line) {
    $ln = $n + 1;
    if (preg_match('/footer|@include|copyright|©|hak|sosial|telp|telepon|follow|social|email@|<footer|bg-slate-900 py|pt-16/i', $line)) {
        echo $ln . ': ' . $line;
    }
}
echo "\n===== LANDINGPAGE last </body>/</html> =====\n";
foreach ($a as $n => $line) {
    $ln = $n + 1;
    if (preg_match('#</body>|</html>#', $line)) {
        echo $ln . ': ' . $line;
    }
}
