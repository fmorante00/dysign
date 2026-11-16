<x-admin-layout>
    <style>
        .dy-notif {--dy-navy:#101064;--dy-gold:#D4A017;--dy-muted:#6b7894;}
        .dy-notif *, .dy-notif *::before, .dy-notif *::after {box-sizing:border-box}
        .dy-notif .dy-hero { background:linear-gradient(105deg,rgba(13,13,76,.96),rgba(16,16,100,.82) 63%,rgba(16,16,100,.67)), url('{{ asset('images/school.jpg') }}') center / cover; border-bottom:3px solid var(--dy-gold);border-radius:23px;color:white;padding:32px 35px; }
        .dy-notif .dy-card {background:white;border:1px solid #e5eaf3;border-radius:18px;box-shadow:0 4px 16px rgba(16,16,100,.04);}
        .dy-notif .dy-filter {display:inline-flex;align-items:center;gap:7px;border-radius:12px;padding:10px 15px;background:#fff;border:1px solid #dfe5f0;color:#28335e;font-size:13px;font-weight:650;text-decoration:none}
        .dy-notif .dy-filter[aria-current="page"] {background:var(--dy-navy);border-color:var(--dy-navy);color:#fff}
        .dy-notif .dy-item {display:flex;gap:15px;padding:18px 22px;align-items:flex-start;border-top:1px solid #edf0f6}
        .dy-notif .dy-item:first-child {border-top:0}
        .dy-notif .dy-item.unread {background:#f7f8ff}
        .dy-notif .dy-mark {background:#f2f4fb;border-radius:10px;border:1px solid #e1e7f1;padding:7px 10px;color:#101064;font-size:12px;font-weight:700;cursor:pointer;white-space:nowrap}
        .dy-notif a.dy-open {text-decoration:none;flex:1;min-width:0;display:block}
        .dy-notif .dy-open:hover .dy-title {text-decoration:underline}
        .dy-notif .dy-title {font-size:15px;font-weight:800;color:#101064}
        .dy-notif .dy-text {font-size:13px;color:#455372;line-height:1.55;margin-top:5px;word-break:break-word}
        .dy-notif .dy-meta {font-size:11px;color:#7a879e;margin-top:9px}
        .dy-notif .dy-panel-head {display:flex;justify-content:space-between;align-items:center;gap:12px;padding:20px 22px;flex-wrap:wrap}
        @media(max-width:640px){.dy-notif .dy-hero{padding:23px}.dy-notif .dy-item{padding:16px;gap:9px;flex-wrap:wrap}.dy-notif .dy-mark{margin-left:24px}}
    </style>
    <main class="dy-notif mx-auto w-full max-w-[1500px] px-4 pb-12 pt-7 sm:px-7">
        <section class="dy-hero">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[.22em] text-[#efc44c]">DySign / Notification Center</p>
                    <h1 class="text-3xl font-extrabold tracking-tight">My Notifications</h1>
                    <p class="mt-2 max-w-xl text-sm text-white/80">Important updates about your events, attendance, requests, and account—only those relevant to you.</p>
                </div>
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-white/30 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/20">Back to Dashboard</a>
            </div>
        </section>
        <div class="mt-5 grid grid-cols-2 gap-3 sm:max-w-xl">
            <div class="dy-card p-5"><p class="text-xs font-semibold text-[#697b9b]">All Notifications</p><p class="mt-2 text-3xl font-extrabold text-[#101064]">{{ $totalCount }}</p></div>
            <div class="dy-card p-5"><p class="text-xs font-semibold text-[#697b9b]">Unread Notifications</p><p class="mt-2 text-3xl font-extrabold text-[#b8860b]">{{ $unreadCount }}</p></div>
        </div>
        <div class="dy-card mt-5 p-4 sm:p-5">
            <div class="flex flex-wrap items-center gap-2">
                @foreach ($categories as $option)
                    <a class="dy-filter" aria-current="{{ $category === $option ? 'page' : 'false' }}" href="{{ route('notifications.index', ['category' => $option, 'status' => $status]) }}">{{ $option }}</a>
                @endforeach
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-between gap-3 border-t border-[#eef1f5] pt-4">
                <div class="flex gap-2">
                    @foreach (['all'=>'All','unread'=>'Unread','read'=>'Read'] as $key => $name)
                        <a class="dy-filter" aria-current="{{ $status === $key ? 'page' : 'false' }}" href="{{ route('notifications.index', ['category' => $category, 'status' => $key]) }}">{{ $name }}</a>
                    @endforeach
                </div>
                @if ($unreadCount > 0)
                    <form method="POST" action="{{ route('notifications.read-all') }}" onsubmit="return confirm('Mark all notifications as read?');">
                        @csrf
                        <button class="rounded-xl bg-[#101064] px-4 py-2.5 text-sm font-bold text-white hover:bg-[#202078]">Mark All as Read</button>
                    </form>
                @endif
            </div>
        </div>
        <section class="dy-card mt-5 overflow-hidden">
            <div class="dy-panel-head"><div><h2 class="text-lg font-extrabold text-[#101064]">Activity Updates</h2><p class="mt-1 text-xs text-[#697b9b]">{{ $notifications->total() }} matching notifications · newest first</p></div><span class="text-xs text-[#697b9b]">Page {{ $notifications->currentPage() }} of {{ max(1,$notifications->lastPage()) }}</span></div>
            @forelse ($notifications as $notification)
                @php
                    $data = $notification->data;
                    $title = data_get($data, 'title', 'DySign Update');
                    $message = data_get($data, 'message', '');
                    $kind = data_get($data, 'category', 'System');
                    $unread = $notification->read_at === null;
                @endphp
                <article class="dy-item {{ $unread ? 'unread' : '' }}">
                    <span class="mt-[9px] block h-2.5 w-2.5 shrink-0 rounded-full {{ $unread ? 'bg-[#D4A017]' : 'bg-[#cbd5e1]' }}"></span>
                    <a class="dy-open" href="{{ route('notifications.open', $notification->id) }}">
                        <div class="dy-title">{{ $title }} @if($unread)<span class="ml-1 rounded-md bg-[#fff1c7] px-2 py-1 align-middle text-[10px] text-[#765100]">New</span>@endif</div>
                        <p class="dy-text">{{ $message }}</p>
                        <p class="dy-meta"><span class="font-bold text-[#aa7f15]">{{ $kind }}</span> · {{ $notification->created_at?->diffForHumans() }}</p>
                    </a>
                    @if($unread)
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">@csrf<button class="dy-mark" type="submit">Mark Read</button></form>
                    @endif
                </article>
            @empty
                <div class="px-6 py-16 text-center"><div class="text-3xl">🔔</div><h3 class="mt-3 font-extrabold text-[#101064]">No notifications here</h3><p class="mt-2 text-sm text-[#687691]">Your relevant updates will appear automatically after successful actions.</p></div>
            @endforelse
            @if($notifications->hasPages())
                <div class="border-t border-[#edf0f6] px-5 py-4">{{ $notifications->links() }}</div>
            @endif
        </section>
    </main>
</x-admin-layout>
