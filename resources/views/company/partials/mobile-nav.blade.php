{{-- Company Mobile Navigation - Minimalist (< md) - Tidak mengganggu desktop --}}
@auth
@if(Auth::user()->role === 'company')
{{-- Bottom Navigation Bar - Hanya mobile company --}}
<nav class="company-mobile-nav block md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border-t border-blue-100 dark:border-slate-800 shadow-[0_-10px_40px_-10px_rgba(59,130,246,0.15)] safe-area-pb" aria-label="Navigasi Mobile Company">
    <div class="flex items-center justify-around px-2 py-2">
        <a href="{{ route('company.dashboard') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl transition {{ request()->routeIs('company.dashboard') ? 'text-brand bg-blue-50 dark:bg-slate-800' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-house text-[16px]"></i>
            <span class="text-[9px] font-bold tracking-wide">Home</span>
        </a>
        <a href="{{ route('company.projects.index') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl transition {{ request()->routeIs('company.projects.index') || request()->routeIs('company.projects.show') || request()->routeIs('company.projects.edit') ? 'text-brand bg-blue-50 dark:bg-slate-800' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-folder-open text-[16px]"></i>
            <span class="text-[9px] font-bold tracking-wide">Proyek</span>
        </a>
        {{-- Tombol tengah FAB Tambah Proyek --}}
        <a href="{{ route('company.projects.create') }}" class="flex flex-col items-center -mt-6">
            <span class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white flex items-center justify-center shadow-lg shadow-blue-500/30 border-4 border-white dark:border-slate-900 {{ request()->routeIs('company.projects.create') ? 'scale-105' : '' }}">
                <i class="fa-solid fa-plus text-lg"></i>
            </span>
            <span class="text-[9px] font-bold text-slate-500 mt-1">Tambah</span>
        </a>
        <a href="{{ route('company.workspaces.index') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl transition {{ request()->routeIs('company.workspaces.*') ? 'text-brand bg-blue-50 dark:bg-slate-800' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-layer-group text-[16px]"></i>
            <span class="text-[9px] font-bold tracking-wide">Workspace</span>
        </a>
        <a href="{{ route('company.reports.index') }}" class="flex flex-col items-center gap-1 px-3 py-1.5 rounded-xl transition {{ request()->routeIs('company.reports.*') ? 'text-brand bg-blue-50 dark:bg-slate-800' : 'text-slate-400 hover:text-slate-600' }}">
            <i class="fa-solid fa-flag text-[16px]"></i>
            <span class="text-[9px] font-bold tracking-wide">Laporan</span>
        </a>
    </div>
</nav>

<style>
/* Safe area untuk HP dengan notch */
.safe-area-pb { padding-bottom: env(safe-area-inset-bottom); }
/* Cegah horizontal scroll di mobile company */
@media (max-width: 767px) {
  html, body { overflow-x: hidden; }
  /* Beri ruang bawah agar konten tidak tertutup bottom nav */
  main { padding-bottom: 5.5rem !important; }
}
</style>
<script>
// Sinkronkan tombol notifikasi & profil mobile dengan dropdown desktop yang sudah ada
document.addEventListener('DOMContentLoaded', function(){
  const mobileBtn = document.getElementById('notificationButtonMobile');
  const desktopBtn = document.getElementById('notificationButton');
  const desktopDropdown = document.getElementById('notificationDropdown');
  if(mobileBtn && desktopBtn && desktopDropdown){
    mobileBtn.addEventListener('click', function(e){
      e.stopPropagation();
      desktopBtn.click();
      // Sync badge angka
      const badge = document.getElementById('notificationBadge');
      const mobileBadge = document.getElementById('notificationBadgeMobile');
      if(badge && mobileBadge){
        const count = badge.textContent.trim();
        if(count && badge.classList.contains('hidden')===false){
          mobileBadge.textContent = count;
          mobileBadge.classList.remove('hidden');
        }
      }
    });
  }
  const userMobileBtn = document.getElementById('userButtonMobile');
  const userDesktopBtn = document.getElementById('userButton');
  if(userMobileBtn && userDesktopBtn){
    userMobileBtn.addEventListener('click', function(e){
      e.stopPropagation();
      userDesktopBtn.click();
    });
  }
  // Sync badge via observer
  const badge = document.getElementById('notificationBadge');
  const mobileBadge = document.getElementById('notificationBadgeMobile');
  if(badge && mobileBadge){
    const obs = new MutationObserver(function(){
      mobileBadge.textContent = badge.textContent;
      mobileBadge.className = badge.className.replace('hidden','') + (badge.classList.contains('scale-0') || badge.classList.contains('hidden') ? ' hidden' : '');
      if(badge.textContent.trim()==='0' || badge.classList.contains('hidden') || badge.classList.contains('scale-0')){
        mobileBadge.classList.add('hidden');
      } else {
        mobileBadge.classList.remove('hidden');
      }
    });
    obs.observe(badge, {attributes:true, childList:true, characterData:true});
  }
});
</script>
@endif
@endauth
