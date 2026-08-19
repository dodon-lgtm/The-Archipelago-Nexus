$file = "c:\xampp\htdocs\The-Archipelago-Nexus\resources\views\help\index.blade.php"
$content = Get-Content $file -Raw

# Fix duplicate dark:text-slate-100 class
$content = $content -replace 'dark:text-slate-100 dark:text-slate-100', 'dark:text-slate-100'

Set-Content -Path $file -Value $content -Encoding utf8
Write-Output "Fixed duplicate dark:text-slate-100 classes"