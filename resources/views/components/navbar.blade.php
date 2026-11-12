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

DySign Administration Portal

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
    x-data="{ notificationsOpen: false }"
    @click.outside="notificationsOpen = false"
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
        @if ($unreadNotificationCount > 0)
            <span
                class="absolute -right-3 -top-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white"
            >
                {{ $unreadNotificationCount > 99 ? '99+' : $unreadNotificationCount }}
            </span>
        @endif

    </button>

    <!-- Notification Dropdown -->
    <div
        x-cloak
        x-show="notificationsOpen"
        x-transition
        class="absolute right-0 z-[100] mt-5 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl sm:w-96"
    >

        <!-- Dropdown Header -->
        <div class="flex items-center justify-between border-b border-gray-100 bg-[#101064] px-5 py-4">

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
        <div class="max-h-96 overflow-y-auto">

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
                    href="{{ $notificationUrl }}"
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
        <div class="border-t border-gray-100 bg-gray-50 px-5 py-3">

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

            <p class="mt-2 text-center text-[11px] text-gray-400">
                Showing your 10 most recent notifications
            </p>

        </div>

    </div>

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




<!-- AVATAR -->

<div

class="
w-10
h-10
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-semibold
"

>

{{ strtoupper(substr(auth()->user()->name,0,1)) }}

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

href="#"

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
