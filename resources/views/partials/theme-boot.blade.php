{{-- Apply theme before paint. Canonical key: theme_user_{id}. --}}
<script>
(function () {
    var userId = @json(Auth::id());
    var key = userId ? ('theme_user_' + userId) : 'theme_user_';
    var stored = localStorage.getItem(key);

    if (userId && stored !== 'dark' && stored !== 'light') {
        var legacy = localStorage.getItem('theme') || localStorage.getItem('apexforge_theme');
        if (legacy === 'dark' || legacy === 'light') {
            localStorage.setItem(key, legacy);
            stored = legacy;
        }
    }

    if (stored === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();
</script>
@include('partials.theme-dark-overrides')
