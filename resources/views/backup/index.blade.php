<x-admin-layout>
@php
    $stats = $stats ?? ['total' => 0, 'completed' => 0, 'failed' => 0, 'processing' => 0];
    $search = $search ?? '';
    $status = $status ?? 'all';
    $perPage = $perPage ?? 10;
    $dateFrom = $dateFrom ?? '';
    $dateTo = $dateTo ?? '';
@endphp

<style>
    .dy-backup-banner {
        background: linear-gradient(100deg, rgba(13,16,82,.97), rgba(18,20,91,.91) 53%, rgba(15,17,84,.76)),
            url('{{ asset('images/school.jpg') }}') center/cover no-repeat;
    }
    .dy-backup-card { border: 1px solid #e2e8f0; border-radius: 20px; background: #fff; box-shadow: 0 6px 18px rgba(15,23,42,.035); }
    .dy-backup-kicker { color: #b47f00; font-weight: 800; text-transform: uppercase; letter-spacing: .2em; font-size: 10px; }
    .dy-backup-label { display: block; margin-bottom: 7px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: #7c8cab; font-size: 10px; }
    .dy-backup-control { width: 100%; min-height: 42px; background: white; border: 1px solid #dce3ed; border-radius: 12px; padding: 10px 12px; font-size: 13px; color: #101064; }
    .dy-backup-control:focus-visible { outline: 2px solid #d4a017; outline-offset: 2px; }
    .dy-backup-table th { background: #f8fafc; color: #7886a0; font-size: 10px; letter-spacing: .13em; text-transform: uppercase; font-weight: 800; padding: 16px 20px; text-align: left; }
    .dy-backup-table td { padding: 17px 20px; vertical-align: middle; color: #334155; border-top: 1px solid #edf1f6; font-size: 13px; }
    .dy-backup-table tbody tr:hover { background: #fafbff; }
</style>

<div class="min-w-0 space-y-7 pb-8">
    {{-- Existing shared DySign layout displays session feedback. Avoid duplicate page alerts. --}}
    <section class="dy-backup-banner relative isolate overflow-hidden rounded-[28px] px-6 py-8 text-white shadow-[0_14px_30px_rgba(16,16,100,.10)] sm:px-9 lg:px-10 lg:py-9">
        <div aria-hidden="true" class="pointer-events-none absolute -right-14 -top-32 h-72 w-72 rounded-full border border-white/15"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -right-4 -top-16 h-52 w-52 rounded-full border border-white/10"></div>
        <div aria-hidden="true" class="pointer-events-none absolute -bottom-28 right-56 h-60 w-60 rounded-full border border-[#D4A017]/20"></div>
        <div class="absolute inset-x-0 bottom-0 h-[3px] bg-[#D4A017]"></div>
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
            <div class="max-w-3xl">
                <p class="text-[10px] font-extrabold uppercase tracking-[.28em] text-[#f3cd56]">SYSTEM MANAGEMENT / DATA PROTECTION</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Backup & Recovery</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">Create database snapshots, review backup history, and prepare a controlled recovery when needed.</p>
                <div class="mt-5 flex flex-wrap items-center gap-3 text-xs text-white/80">
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5">Administrator access</span>
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5">SQL database backups</span>
                </div>
            </div>
            <a href="#backup-history" class="inline-flex items-center gap-2 rounded-xl bg-[#D4A017] px-5 py-3 text-sm font-bold text-[#101064] shadow-sm transition hover:bg-[#edbf36]">View backup history <span aria-hidden="true">→</span></a>
        </div>
    </section>

    @if($latestRecord?->status === 'Failed')
        <div role="alert" class="flex flex-wrap items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900">
            <span class="mt-0.5 text-lg" aria-hidden="true">⚠</span>
            <div class="min-w-0 flex-1"><strong class="font-bold">Latest backup attempt failed.</strong> Review the backup history before relying on the latest snapshot.</div>
        </div>
    @elseif($latestRecord?->status === 'Processing')
        <div role="status" class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">A backup attempt is marked as Processing. Refresh to check its status.</div>
    @endif

    <section aria-labelledby="backup-summary-title" class="space-y-4">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div><p class="dy-backup-kicker">Protection overview</p><h2 id="backup-summary-title" class="mt-1 text-lg font-extrabold text-[#101064]">Backup Status at a Glance</h2><p class="mt-1 text-xs text-slate-500">Counts reflect all recorded backup attempts, not just this page.</p></div>
            <span class="rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $storageUsed }} recorded storage</span>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="dy-backup-card p-5"><div class="flex items-start justify-between gap-3"><div><p class="dy-backup-label">Total Backups</p><p class="text-3xl font-extrabold tabular-nums text-[#101064]">{{ number_format($stats['total']) }}</p></div><span class="grid h-10 w-10 place-items-center rounded-xl bg-indigo-50 text-[#101064]">▤</span></div><p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">All backup attempts</p></div>
            <div class="dy-backup-card p-5"><div class="flex items-start justify-between gap-3"><div><p class="dy-backup-label">Completed</p><p class="text-3xl font-extrabold tabular-nums text-emerald-700">{{ number_format($stats['completed']) }}</p></div><span class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-700">✓</span></div><p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">SQL export finished successfully</p></div>
            <div class="dy-backup-card p-5"><div class="flex items-start justify-between gap-3"><div><p class="dy-backup-label">Failed</p><p class="text-3xl font-extrabold tabular-nums text-rose-600">{{ number_format($stats['failed']) }}</p></div><span class="grid h-10 w-10 place-items-center rounded-xl bg-rose-50 text-rose-600">!</span></div><p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Attempts requiring review</p></div>
            <div class="dy-backup-card p-5"><div class="flex items-start justify-between gap-3"><div><p class="dy-backup-label">Processing</p><p class="text-3xl font-extrabold tabular-nums text-amber-600">{{ number_format($stats['processing']) }}</p></div><span class="grid h-10 w-10 place-items-center rounded-xl bg-amber-50 text-amber-700">◷</span></div><p class="mt-4 border-t border-slate-100 pt-3 text-xs text-slate-500">Attempts not marked completed</p></div>
        </div>
    </section>

    <section class="grid gap-4 xl:grid-cols-[1.2fr_1fr]">
        <div class="dy-backup-card overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-5"><p class="dy-backup-kicker">Backup control</p><h2 class="mt-1 text-lg font-extrabold text-[#101064]">Create Database Backup</h2><p class="mt-1 text-sm text-slate-500">Generate a new SQL snapshot using the existing database backup service.</p></div>
            <div class="space-y-5 p-6">
                <div class="flex items-start gap-4 rounded-2xl border border-indigo-100 bg-[#f7f8fe] p-4"><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white font-bold text-[#101064]">↓</span><div><p class="font-bold text-[#101064]">Latest completed backup</p><p class="mt-1 text-sm text-slate-600">@if($lastBackup) {{ $lastBackup->created_at?->format('M d, Y • h:i A') }} @else No completed backup recorded yet. @endif</p><p class="mt-1 text-xs text-slate-500">A completed export does not guarantee a successful restore; test your backups separately.</p></div></div>
                <div class="flex flex-wrap items-center justify-between gap-3"><p class="max-w-lg text-xs leading-5 text-slate-500">Backups are stored on the server. Download and secure an offline copy, especially before important database changes.</p>
                    <form method="POST" action="{{ route('backup.store') }}"
                        data-confirm
                        data-confirm-title="Create a new database backup?"
                        data-confirm-message="Export the current database to a new SQL backup file. Existing backups will be kept."
                        data-confirm-confirm-text="Create Backup"
                        data-confirm-variant="default">
                        @csrf
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#101064] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0c0c4f]">+ Create Backup</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="dy-backup-card overflow-hidden">
            <div class="border-b border-slate-100 px-6 py-5"><p class="dy-backup-kicker">Recovery readiness</p><h2 class="mt-1 text-lg font-extrabold text-[#101064]">Restore Safely</h2><p class="mt-1 text-sm text-slate-500">A supervised recovery checklist for administrators.</p></div>
            <div class="space-y-3 px-6 py-5">
                <div class="flex items-start gap-3"><span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#f0f1ff] text-xs font-bold text-[#101064]">1</span><div><p class="text-sm font-bold text-[#101064]">Download a completed SQL backup</p><p class="mt-1 text-xs leading-5 text-slate-500">Keep a protected copy outside the application server.</p></div></div>
                <div class="flex items-start gap-3"><span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#f0f1ff] text-xs font-bold text-[#101064]">2</span><div><p class="text-sm font-bold text-[#101064]">Verify it in a test environment</p><p class="mt-1 text-xs leading-5 text-slate-500">Confirm the SQL can be restored and the records are usable.</p></div></div>
                <div class="flex items-start gap-3"><span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#f0f1ff] text-xs font-bold text-[#101064]">3</span><div><p class="text-sm font-bold text-[#101064]">Schedule authorized production recovery</p><p class="mt-1 text-xs leading-5 text-slate-500">Take a fresh backup and have the database administrator perform the controlled restore.</p></div></div>
                <p class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-xs leading-5 text-amber-900"><strong>Manual recovery only:</strong> This module does not currently perform one-click database restoration. Downloading a backup will not alter live data.</p>
            </div>
        </div>
    </section>

    <section id="backup-history" aria-labelledby="backup-history-heading" class="space-y-4 scroll-mt-24">
        <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="dy-backup-kicker">Backup archive</p><h2 id="backup-history-heading" class="mt-1 text-lg font-extrabold text-[#101064]">Backup History</h2><p class="mt-1 text-sm text-slate-500">Search and review recorded SQL backups before downloading.</p></div><span class="rounded-full bg-[#f0f2ff] px-3 py-1.5 text-xs font-bold text-[#101064]">{{ number_format($backups->total()) }} matching records</span></div>
        <div class="dy-backup-card overflow-hidden">
            <form method="GET" action="{{ route('backup.index') }}" class="border-b border-slate-100 p-5 sm:p-6">
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <label class="block xl:col-span-2"><span class="dy-backup-label">Search backup file or creator</span><input class="dy-backup-control" type="search" name="search" value="{{ $search }}" maxlength="100" placeholder="Filename or staff name"></label>
                    <label class="block"><span class="dy-backup-label">Status</span><select class="dy-backup-control" name="status"><option value="all">All statuses</option>@foreach(['Completed', 'Processing', 'Failed'] as $state)<option value="{{ $state }}" @selected($status === $state)>{{ $state }}</option>@endforeach</select></label>
                    <label class="block"><span class="dy-backup-label">From date</span><input class="dy-backup-control" type="date" name="from" value="{{ $dateFrom }}"></label>
                    <label class="block"><span class="dy-backup-label">To date</span><input class="dy-backup-control" type="date" name="to" value="{{ $dateTo }}"></label>
                </div>
                <div class="mt-4 flex flex-wrap items-end justify-between gap-3">
                    <label class="block"><span class="dy-backup-label">Rows per page</span><select name="per_page" class="dy-backup-control min-w-[135px]">@foreach([10,25,50] as $size)<option value="{{ $size }}" @selected($perPage === $size)>{{ $size }} records</option>@endforeach</select></label>
                    <div class="flex flex-wrap gap-2"><a href="{{ route('backup.index') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50">Clear filters</a><button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#101064] px-5 py-2.5 text-sm font-bold text-white hover:bg-[#0c0c4f]">Apply Filters</button></div>
                </div>
            </form>
            <div class="overflow-x-auto">
                <table class="dy-backup-table w-full min-w-[920px]"><thead><tr><th>Backup date</th><th>SQL backup file</th><th>Created by</th><th>Status</th><th>Availability</th><th class="text-right">Action</th></tr></thead>
                    <tbody>
                    @forelse($backups as $backup)
                        @php
                            $size = (int) ($backup->size_bytes ?? 0);
                            $formattedSize = $size >= 1048576 ? number_format($size / 1048576, 2) . ' MB' : ($size >= 1024 ? number_format($size / 1024, 2) . ' KB' : number_format($size) . ' B');
                            $canDownload = $backup->status === 'Completed' && $backup->file_available;
                        @endphp
                        <tr>
                            <td class="whitespace-nowrap"><p class="font-bold text-[#101064]">{{ $backup->created_at?->format('M d, Y') }}</p><p class="mt-1 text-xs text-slate-500">{{ $backup->created_at?->format('h:i A') }}</p></td>
                            <td><p class="max-w-[300px] truncate font-bold text-[#101064]" title="{{ $backup->filename }}">{{ $backup->filename }}</p><p class="mt-1 text-xs text-slate-500">{{ $formattedSize }}</p></td>
                            <td><span class="font-medium">{{ $backup->creator?->name ?? 'System' }}</span></td>
                            <td>@if($backup->status === 'Completed')<span class="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">Completed</span>@elseif($backup->status === 'Failed')<span class="rounded-full bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700">Failed</span>@else<span class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700">Processing</span>@endif</td>
                            <td>@if($canDownload)<span class="text-xs font-bold text-emerald-700">File available</span>@elseif($backup->status === 'Completed')<span class="text-xs font-semibold text-rose-600">File missing</span>@else<span class="text-xs text-slate-500">Not available</span>@endif</td>
                            <td class="text-right">@if($canDownload)<a href="{{ route('backup.download', $backup->backup_id) }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl border border-[#d9ddf3] bg-[#f7f8ff] px-4 py-2.5 text-xs font-bold text-[#101064] transition hover:border-[#D4A017] hover:bg-amber-50">↓ Download</a>@else<span class="text-xs font-semibold text-slate-400">Unavailable</span>@endif</td>
                        </tr>
                        @if($backup->status === 'Failed' && $backup->error_message)
                            <tr><td colspan="6" class="!bg-rose-50/50 text-xs text-rose-700"><strong>Failed attempt:</strong> {{ \Illuminate\Support\Str::limit($backup->error_message, 240) }}</td></tr>
                        @endif
                    @empty
                        <tr><td colspan="6" class="py-14 text-center"><p class="font-bold text-[#101064]">No backup records found</p><p class="mt-2 text-sm text-slate-500">Try changing the filters or create a new backup.</p></td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-5 py-4 text-xs text-slate-500 sm:px-6"><span>Showing {{ $backups->firstItem() ?? 0 }}–{{ $backups->lastItem() ?? 0 }} of {{ $backups->total() }} records</span><span>Only completed backups with available files can be downloaded.</span></div>
            @if($backups->hasPages())<div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $backups->onEachSide(1)->links() }}</div>@endif
        </div>
    </section>
</div>
</x-admin-layout>
