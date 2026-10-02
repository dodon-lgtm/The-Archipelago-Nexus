@auth
<div id="appNotificationToasts"
     class="fixed top-4 right-4 z-[210] flex flex-col gap-3 pointer-events-none max-w-[calc(100vw-2rem)] w-[min(22rem,calc(100vw-2rem))] sm:left-auto left-4"
     aria-live="polite"></div>
<script>
    window.AppNotificationToastsConfig = {
        userId: @json((string) Auth::id()),
        indexUrl: @json(route('notifications.index')),
        readUrlTemplate: @json(url('/notifications/__ID__/read')),
        csrf: @json(csrf_token())
    };
</script>
<script src="{{ asset('js/notification-toasts.js') }}" defer></script>
@endauth
