$file = "c:\xampp\htdocs\The-Archipelago-Nexus\resources\views\help\index.blade.php"
$content = Get-Content $file -Raw

# FAQ icon backgrounds - add dark variants
$content = $content -replace 'bg-blue-50 text-blue-600 flex items-center justify-center', 'bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center'
$content = $content -replace 'bg-indigo-50 text-indigo-600 flex items-center justify-center', 'bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center'
$content = $content -replace 'bg-sky-50 text-sky-600 flex items-center justify-center', 'bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center'
$content = $content -replace 'bg-emerald-50 text-emerald-600 flex items-center justify-center', 'bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center'

Set-Content -Path $file -Value $content -Encoding utf8
Write-Output "Added dark variants to FAQ icons"