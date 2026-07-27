<?php
$f = 'c:/xampp/htdocs/The-Archipelago-Nexus/resources/views/landingpage.blade.php';
$c = file_get_contents($f);
echo "Size: " . strlen($c) . " bytes\n";
echo "First 100 chars: " . substr($c, 0, 100) . "\n";
echo "Last 100 chars: " . substr($c, -100) . "\n";
echo "Contains </html>: " . (strpos($c, '</html>') !== false ? 'YES' : 'NO') . "\n";
echo "Contains @foreach: " . (substr_count($c, '@foreach') . " times") . "\n";
echo "Contains route landing: " . (strpos($c, "route('landing')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains route login: " . (strpos($c, "route('login')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains route register: " . (strpos($c, "route('register')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains route logout: " . (strpos($c, "route('logout')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains route freelancer.dashboard: " . (strpos($c, "route('freelancer.dashboard')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains route company.dashboard: " . (strpos($c, "route('company.dashboard')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains route admin.dashboard: " . (strpos($c, "route('admin.dashboard')") !== false ? 'YES' : 'NO') . "\n";
echo "Contains Auth::user(): " . (strpos($c, 'Auth::user()') !== false ? 'YES' : 'NO') . "\n";
echo "Contains @auth: " . (strpos($c, '@auth') !== false ? 'YES' : 'NO') . "\n";
echo "Contains @endauth: " . (strpos($c, '@endauth') !== false ? 'YES' : 'NO') . "\n";
echo "Contains 2026: " . (strpos($c, '2026') !== false ? 'YES' : 'NO') . "\n";
echo "Contains images/nexus.jpg: " . (strpos($c, 'images/nexus.jpg') !== false ? 'YES' : 'NO') . "\n";
echo "Contains images/gedung.jpg: " . (strpos($c, 'images/gedung.jpg') !== false ? 'YES' : 'NO') . "\n";
echo "Contains images/beranda.png: " . (strpos($c, 'images/beranda.png') !== false ? 'YES' : 'NO') . "\n";
echo "Contains images/image.png: " . (strpos($c, 'images/image.png') !== false ? 'YES' : 'NO') . "\n";
echo "Contains number_format: " . (strpos($c, 'number_format') !== false ? 'YES' : 'NO') . "\n";
echo "Contains Carbon: " . (strpos($c, 'Carbon') !== false ? 'YES' : 'NO') . "\n";
echo "Contains optional(): " . (strpos($c, 'optional(') !== false ? 'YES' : 'NO') . "\n";
echo "Contains Str::limit: " . (strpos($c, 'Str::limit') !== false ? 'YES' : 'NO') . "\n";
?>
