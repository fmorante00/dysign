<div
    x-data="
    {
        scrolled:false,
        open:false
    }
    "

    @scroll.window="
        scrolled = window.scrollY > 20
    "

    class="
        sticky
        top-0
        z-50
        transition
        duration-300
    "

    :class="
        scrolled
        ?
        'bg-white/70 backdrop-blur-lg shadow-md'
        :
        'bg-white'
    "
>


<div class="
    h-24
    px-8
    flex
    items-center
    justify-between
">


<!-- LEFT SIDE -->

<div>

<h1 class="
    text-xl
    font-bold
    text-[#101064]
">

{{ ['Student Account' => 'DySign Student Portal', 'Attendance Personnel' => 'DySign Attendance Personnel Portal', 'Department Staff' => 'DySign Department Staff Portal'][auth()->user()?->role?->role_name ?? ''] ?? 'DySign Administration Portal' }}

</h1>


<p class="
    text-sm
    text-gray-500
">

Digital Identity Management System

</p>


</div>





<!-- RIGHT SIDE -->


<div class="
    flex
    items-center
    gap-6
">






<!-- DYNAMIC NOTIFICATIONS -->
<style>
.dy-notif-overlay { position:fixed !important; inset:0 !important; z-index:2147483000 !important; pointer-events:none; }
.dy-notif-overlay[x-cloak] { display:none !important; }
.dy-notif-backdrop { position:absolute; inset:0; background:rgba(8,11,44,.45); pointer-events:auto; }
.dy-notif-drawer { position:absolute; top:0; right:0; width:min(440px,100vw); height:100vh; height:100dvh; background:#fff; display:flex; flex-direction:column; overflow:hidden; pointer-events:auto; box-shadow:-20px 0 65px rgba(12,8,56,.28); border-left:1px solid #dfd7f3; animation:dy-notif-enter .23s ease-out both; }
@keyframes dy-notif-enter { from { transform:translateX(100%); opacity:.65; } to { transform:translateX(0); opacity:1; } }
.dy-notif-drawer .dy-notif-header { position:relative; display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex:0 0 auto; padding:27px 62px 23px 24px; background:linear-gradient(145deg,#41159b 0%,#24106b 58%,#101064 100%); border-bottom:4px solid #e2a919; color:white; }
.dy-notif-drawer .dy-notif-header h3 { color:#fff; font-size:20px; }
.dy-notif-drawer .dy-notif-header p { color:#e1d9f7; }
.dy-notif-close { position:absolute; top:25px; right:24px; display:grid; place-items:center; width:32px; height:32px; border:1px solid rgba(255,255,255,.32); border-radius:9px; color:#fff; background:rgba(255,255,255,.09); font-size:23px; cursor:pointer; line-height:1; }
.dy-notif-close:hover { background:rgba(255,255,255,.2); }
.dy-notif-drawer .dy-notif-list { flex:1 1 auto; min-height:0; max-height:none !important; overflow-y:auto; background:#fff; }
.dy-notif-drawer .dy-notif-list > a { border-color:#efedf7; padding-top:16px; padding-bottom:16px; }
.dy-notif-drawer .dy-notif-list > a:hover { background:#f4f0ff; }
.dy-notif-drawer .dy-notif-footer { flex:0 0 auto; padding:18px 24px 23px; background:#faf8ff; border-top:1px solid #e6ddf7; }
.dy-notif-drawer .dy-notif-footer a { display:block; width:100%; background:#301080; border:0; color:white; border-radius:11px; padding:12px 16px; text-align:center; font-weight:700; box-shadow:none; }
.dy-notif-drawer .dy-notif-footer a:hover { background:#4a1ba3; }
@media (max-width:480px) { .dy-notif-drawer { width:100vw; border-left:0; } .dy-notif-drawer .dy-notif-header { padding-top:22px; } }
@media (prefers-reduced-motion:reduce) { .dy-notif-drawer { animation:none; } }
</style>

@php
    $currentUser = auth()->user();

    $unreadNotificationCount = $currentUser
        ->unreadNotifications()
        ->count();

    $recentNotifications = $currentUser
        ->notifications()
        ->latest()
        ->limit(10)
        ->get();
@endphp

<div
    class="relative"
    x-data="{
        notificationsOpen: false,
        unread: {{ (int) $unreadNotificationCount }},
        refreshNotifications() {
            fetch('{{ route('notifications.count') }}', {headers:{'Accept':'application/json'},credentials:'same-origin'})
                .then(response => response.ok ? response.json() : null)
                .then(data => {if(data && typeof data.unread === 'number') this.unread = data.unread;})
                .catch(() => {});
        }
    }"
    x-init="setInterval(() => refreshNotifications(), 45000)"

>

    <!-- Notification Bell -->
    <button
        type="button"
        @click="notificationsOpen = !notificationsOpen"
        :aria-expanded="notificationsOpen.toString()"
        aria-label="Toggle notifications"
        class="relative text-[#101064] transition hover:text-[#D4A017]"
    >

        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="22"
            height="22"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        >
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
        </svg>

        <!-- Unread Counter -->
        <span x-cloak x-show="unread > 0" x-text="unread > 99 ? '99+' : unread"
              class="absolute -right-3 -top-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white"></span>

    </button>

    <!-- Notification Side Drawer: teleported outside the sticky navbar -->
    <template x-teleport="body">
        <div x-cloak x-show="notificationsOpen" @keydown.escape.window="notificationsOpen = false" class="dy-notif-overlay">
            <div class="dy-notif-backdrop" @click="notificationsOpen = false" aria-hidden="true"></div>
            <section class="dy-notif-drawer" role="dialog" aria-modal="true" aria-label="Notifications">

        <!-- Dropdown Header -->
        <div class="dy-notif-header">
            <button type="button" class="dy-notif-close" @click="notificationsOpen = false" aria-label="Close notifications">&times;</button>

            <div>
                <h3 class="text-base font-bold text-white">
                    Notifications
                </h3>

                <p class="mt-1 text-xs text-white/70">
                    Your recent DySign activities
                </p>
            </div>

            @if ($unreadNotificationCount > 0)
                <span class="rounded-full bg-[#D4A017] px-3 py-1 text-xs font-bold text-[#101064]">
                    {{ $unreadNotificationCount }} Unread
                </span>
            @endif

        </div>

        <!-- Notification List -->
        <div class="dy-notif-list">

            @forelse ($recentNotifications as $notification)

                @php
                    $notificationData = $notification->data;

                    $notificationTitle = data_get(
                        $notificationData,
                        'title',
                        'System Notification'
                    );

                    $notificationMessage = data_get(
                        $notificationData,
                        'message',
                        ''
                    );

                    $notificationCategory = data_get(
                        $notificationData,
                        'category',
                        'System'
                    );

                    $notificationUrl = data_get(
                        $notificationData,
                        'url',
                        '/dashboard'
                    );

                    // Only allow internal application paths.
                    if (
                        !is_string($notificationUrl) ||
                        !str_starts_with($notificationUrl, '/') ||
                        str_starts_with($notificationUrl, '//')
                    ) {
                        $notificationUrl = route('dashboard');
                    }

                    $isUnread = is_null($notification->read_at);
                @endphp

                <a
                    href="{{ route('notifications.open', $notification->id) }}"
                    class="block border-b border-gray-100 px-5 py-4 transition hover:bg-gray-50 {{ $isUnread ? 'bg-indigo-50/60' : 'bg-white' }}"
                >

                    <div class="flex items-start gap-3">

                        <!-- Status Indicator -->
                        <div class="mt-1.5 shrink-0">

                            <span
                                class="block h-2.5 w-2.5 rounded-full {{ $isUnread ? 'bg-[#D4A017]' : 'bg-gray-300' }}"
                            ></span>

                        </div>

                        <!-- Notification Content -->
                        <div class="min-w-0 flex-1">

                            <div class="flex items-start justify-between gap-2">

                                <p class="text-sm font-bold text-[#101064]">
                                    {{ $notificationTitle }}
                                </p>

                                @if ($isUnread)
                                    <span class="shrink-0 rounded-full bg-[#101064] px-2 py-0.5 text-[10px] font-semibold text-white">
                                        New
                                    </span>
                                @endif

                            </div>

                            <p class="mt-1 text-xs leading-5 text-gray-600">
                                {{ $notificationMessage }}
                            </p>

                            <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] text-gray-400">

                                <span class="font-semibold text-[#D4A017]">
                                    {{ $notificationCategory }}
                                </span>

                                <span>•</span>

                                <span>
                                    {{ $notification->created_at?->diffForHumans() }}
                                </span>

                            </div>

                        </div>

                    </div>

                </a>

                {{-- Mark this notification as read (outside the link) --}}
                @if ($isUnread)
                    <form
                        method="POST"
                        action="{{ route('notifications.read', $notification->id) }}"
                        class="border-b border-gray-100 bg-indigo-50/30 px-5 pb-3 text-right"
                    >
                        @csrf
                        <button
                            type="submit"
                            class="text-xs font-semibold text-[#101064] transition hover:text-[#D4A017]"
                        >
                            ✓ Mark as Read
                        </button>
                    </form>
                @endif

            @empty

                <!-- Empty State -->
                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-[#101064]">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>

                    </div>

                    <p class="mt-4 text-sm font-bold text-[#101064]">
                        No Notifications Yet
                    </p>

                    <p class="mt-2 text-xs text-gray-500">
                        Your system updates will appear here.
                    </p>

                </div>

            @endforelse

        </div>

        <!-- Dropdown Footer -->
        <div class="dy-notif-footer">

            @if ($unreadNotificationCount > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-[#101064] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#202080]"
                    >
                        ✓ Mark All as Read
                    </button>
                </form>
            @else
                <p class="text-center text-xs font-medium text-green-700">
                    ✓ You're all caught up!
                </p>
            @endif

            <a href="{{ route('notifications.index') }}" class="mt-3 block w-full rounded-xl border border-[#101064]/20 bg-white px-4 py-2.5 text-center text-sm font-bold text-[#101064] hover:bg-indigo-50">
                Open Full Notification Center
            </a>
            <p class="mt-2 text-center text-[11px] text-gray-400">Showing your 10 most recent notifications</p>

        </div>

            </section>
        </div>
    </template>

</div>







<!-- USER DROPDOWN -->


<div
class="relative"
@click.outside="open=false"
>




<button
@click="open=!open"

class="
flex
items-center
gap-3
"
>




<!-- AVATAR: use the imported photo for Student Accounts; keep initials as fallback. -->
@php
    $navStudentPhotoUrl = null;
    if ($currentUser?->role?->role_name === 'Student Account') {
        $navPhotoPath = $currentUser?->student?->photo_path;
        $navPhotoFile = is_string($navPhotoPath)
            ? basename(str_replace('\\', '/', $navPhotoPath))
            : '';

        if (
            $navPhotoFile !== ''
            && preg_match('/\A[A-Za-z0-9_-]+\.(?:jpe?g|png|webp)\z/i', $navPhotoFile)
            && is_file(public_path('student_photos/' . $navPhotoFile))
        ) {
            $navStudentPhotoUrl = asset('student_photos/' . $navPhotoFile);
        }
    }
@endphp
<div class="relative flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full border border-[#D4A017]/30 bg-[#101064] font-semibold text-white">
    <span aria-hidden="true">{{ strtoupper(substr($currentUser?->name ?? 'U', 0, 1)) }}</span>
    @if ($navStudentPhotoUrl)
        <img
            src="{{ $navStudentPhotoUrl }}"
            alt="{{ $currentUser->name }} profile photo"
            class="absolute inset-0 h-full w-full object-cover"
            loading="eager"
            decoding="async"
            onerror="this.style.display='none'"
        >
    @endif
</div>






<div class="text-left">


<p

class="
text-sm
font-semibold
text-[#101064]
"

>

{{ auth()->user()->name }}

</p>



<p

class="
text-xs
text-gray-500
"

>

{{ auth()->user()->role->role_name }}

</p>


</div>





<svg
xmlns="http://www.w3.org/2000/svg"
width="16"
height="16"
fill="none"
stroke="currentColor"
stroke-width="2"
>

<path d="m6 9 4 4 4-4"/>

</svg>



</button>

<!-- DROPDOWN MENU -->


<div

x-cloak

x-show="open"

x-transition:enter="
transition
ease-out
duration-200
"

x-transition:enter-start="
opacity-0
scale-95
-translate-y-2
"

x-transition:enter-end="
opacity-100
scale-100
translate-y-0
"


x-transition:leave="
transition
ease-in
duration-150
"

x-transition:leave-start="
opacity-100
scale-100
translate-y-0
"

x-transition:leave-end="
opacity-0
scale-95
-translate-y-2
"


class="
absolute
right-0
mt-4
w-56
bg-white
rounded-xl
shadow-lg
border
border-gray-100
py-2
"

>





<a

href="{{ route('my-profile.show') }}"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
hover:text-[#D4A017]
transition
"

>

View Profile

</a>







<a

href="{{ route('settings.index') }}"

class="
block
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
hover:text-[#D4A017]
transition
"

>

Settings

</a>







<div class="
border-t
my-2
">

</div>







<form

method="POST"

action="{{ route('logout') }}"

>


@csrf



<button

class="
w-full
text-left
px-5
py-3
text-sm
text-gray-700
hover:bg-gray-50
hover:text-red-600
transition
"

>

Logout

</button>


</form>





</div>






</div>






</div>






</div>

</div>
