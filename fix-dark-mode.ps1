$file = "c:\xampp\htdocs\The-Archipelago-Nexus\resources\views\help\index.blade.php"
$content = Get-Content $file -Raw

# FAQ item container - add dark mode classes
$content = $content -replace '(?<=faq-item rounded-2xl border border-slate-200 bg-white) overflow-hidden shadow-sm', ' overflow-hidden shadow-sm dark:border-slate-700 dark:bg-slate-800'

# FAQ section headings
$content = $content -replace 'text-2xl sm:text-3xl font-black text-slate-950', 'text-2xl sm:text-3xl font-black text-slate-950 dark:text-slate-100'

# FAQ section descriptions
$content = $content -replace 'class="text-slate-500 leading-7"', 'class="text-slate-500 dark:text-slate-300 leading-7"'

# FAQ button hover
$content = $content -replace 'hover:bg-slate-50 transition-colors', 'hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors'

# FAQ question text
$content = $content -replace 'font-bold text-slate-900 leading-7', 'font-bold text-slate-900 dark:text-slate-100 leading-7'

# FAQ content text
$content = $content -replace 'px-6 pb-6 text-slate-600 leading-8', 'px-6 pb-6 text-slate-600 dark:text-slate-300 leading-8'

# Category section headings (Akun, Proyek, Pembayaran, Keamanan headings)
$content = $content -replace 'text-slate-900', 'text-slate-900 dark:text-slate-100'

# Category card hover shadows
$content = $content -replace 'hover:shadow-blue-100/40', 'hover:shadow-blue-100/40 dark:hover:shadow-blue-900/30'
$content = $content -replace 'hover:shadow-indigo-100/40', 'hover:shadow-indigo-100/40 dark:hover:shadow-indigo-900/30'
$content = $content -replace 'hover:shadow-sky-100/40', 'hover:shadow-sky-100/40 dark:hover:shadow-sky-900/30'
$content = $content -replace 'hover:shadow-emerald-100/40', 'hover:shadow-emerald-100/40 dark:hover:shadow-emerald-900/30'

# WhatsApp section gradient background
$content = $content -replace 'bg-gradient-to-br from-blue-600 via-blue-600 to-indigo-700', 'bg-gradient-to-br from-blue-600 via-blue-600 to-indigo-700 dark:from-blue-800 dark:via-blue-900 dark:to-indigo-950'

# WhatsApp button
$content = $content -replace 'bg-white text-blue-700', 'bg-white dark:bg-slate-800 dark:text-blue-300'

# Footer links
$content = $content -replace 'hover:text-blue-400', 'hover:text-blue-400 dark:hover:text-blue-300'

Set-Content -Path $file -Value $content -Encoding utf8