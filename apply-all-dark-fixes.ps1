$file = "c:\xampp\htdocs\The-Archipelago-Nexus\resources\views\help\index.blade.php"
$content = Get-Content $file -Raw

# FAQ item containers - add dark border and background
$content = $content -replace 'faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm"', 'faq-item rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800"'

# FAQ item hover
$content = $content -replace 'hover:bg-slate-50 transition-colors', 'hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors'

# FAQ icon backgrounds - add dark variants
$content = $content -replace 'bg-blue-50 text-blue-600 flex items-center justify-center', 'bg-blue-50 dark:bg-slate-700 text-blue-600 dark:text-blue-400 flex items-center justify-center'
$content = $content -replace 'bg-indigo-50 text-indigo-600 flex items-center justify-center', 'bg-indigo-50 dark:bg-slate-700 text-indigo-600 dark:text-indigo-400 flex items-center justify-center'
$content = $content -replace 'bg-sky-50 text-sky-600 flex items-center justify-center', 'bg-sky-50 dark:bg-slate-700 text-sky-600 dark:text-sky-400 flex items-center justify-center'
$content = $content -replace 'bg-emerald-50 text-emerald-600 flex items-center justify-center', 'bg-emerald-50 dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 flex items-center justify-center'

# FAQ question text
$content = $content -replace 'font-bold text-slate-900 leading-7"', 'font-bold text-slate-900 dark:text-slate-100 leading-7"'

# FAQ content text
$content = $content -replace 'px-6 pb-6 text-slate-600 leading-8"', 'px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8"'

# Category cards - add dark border and background
$content = $content -replace 'border border-slate-200 bg-white p-6 hover:border-blue-200', 'border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-blue-200'
$content = $content -replace 'border border-slate-200 bg-white p-6 hover:border-indigo-200', 'border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-indigo-200'
$content = $content -replace 'border border-slate-200 bg-white p-6 hover:border-sky-200', 'border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-sky-200'
$content = $content -replace 'border border-slate-200 bg-white p-6 hover:border-emerald-200', 'border border-slate-200 bg-white dark:border-slate-700 dark:bg-slate-800 p-6 hover:border-emerald-200'

# Category description paragraphs - add dark text
$content = $content -replace 'mt-2 text-sm leading-6 text-slate-500"', 'mt-2 text-sm leading-6 text-slate-500 dark:text-slate-300"'

# Category card headings
$content = $content -replace 'font-extrabold text-lg text-slate-900"', 'font-extrabold text-lg text-slate-900 dark:text-slate-100"'

# Category section description
$content = $content -replace 'text-sm leading-7 text-slate-400', 'text-sm leading-7 text-slate-400 dark:text-slate-300'

# WhatsApp section gradient
$content = $content -replace 'from-blue-600 via-blue-600 to-indigo-700', 'from-blue-600 via-blue-600 to-indigo-700 dark:from-blue-800 dark:via-blue-900 dark:to-indigo-950'

# WhatsApp button
$content = $content -replace 'bg-white text-blue-700', 'bg-white dark:bg-slate-800 dark:text-blue-300'

# Footer copyright text
$content = $content -replace 'text-xs text-slate-500', 'text-xs text-slate-500 dark:text-slate-400'

Set-Content -Path $file -Value $content -Encoding utf8
Write-Output "Applied all remaining dark mode classes"