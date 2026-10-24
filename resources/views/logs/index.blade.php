<x-admin-layout>
@php
    $logs = $logs ?? collect();
    $modules = $modules ?? collect();
    $actions = $actions ?? collect();
    $statuses = $statuses ?? collect();
    $statistics = $statistics ?? ['total' => 0, 'today' => 0, 'success' => 0, 'failed' => 0];
@endphp

<style>
    .logs-hero {
        background: linear-gradient(100deg, rgba(16,16,100,.97), rgba(16,16,100,.81)),
                    url('{{ asset('images/school.jpg') }}') center/cover;
    }
    .audit-detail summary::-webkit-details-marker { display:none; }
    .audit-detail summary { list-style:none; }
</style>

<div class="min-w-0 space-y-7 pb-10">
    <section class="logs-hero relative overflow-hidden rounded-[26px] px-7 py-9 text-white shadow-sm sm:px-9">
        <div class="absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>
        <div class="relative">
            <p class="text-xs font-bold uppercase tracking-[.24em] text-[#E7C75B]">System Management / Module 20</p>
            <h1 class="mt-3 text-3xl font-bold md:text-4xl">Audit Trail & Activity Logs</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-white/80">Review recorded user activities, identify affected records, and examine changes made in DySign.</p>
        </div>
    </section>

    <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        @foreach([
            ['title' => 'All Activities', 'value' => $statistics['total'], 'accent' => '#101064'],
            ['title' => 'Logged Today', 'value' => $statistics['today'], 'accent' => '#D4A017'],
            ['title' => 'Successful', 'value' => $statistics['success'], 'accent' => '#16803C'],
            ['title' => 'Failed', 'value' => $statistics['failed'], 'accent' => '#C23A3A'],
        ] as $metric)
            <div class="rounded-2xl border border-gray-200 bg-white px-5 py-5 shadow-sm">
                <p class="text-xs font-medium text-gray-500">{{ $metric['title'] }}</p>
                <p class="mt-2 text-3xl font-bold" style="color: {{ $metric['accent'] }}">{{ number_format($metric['value']) }}</p>
            </div>
        @endforeach
    </section>

    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
            <div>
                <p class="text-xs font-bold uppercase tracking-[.18em] text-[#A87900]">Activity controls</p>
                <h2 class="mt-1 text-lg font-bold text-[#101064]">Search & Filter Logs</h2>
            </div>
            <a href="{{ route('logs.index') }}" class="text-sm font-semibold text-[#101064] underline-offset-4 hover:underline">Clear filters</a>
        </div>
        <form method="GET" action="{{ route('logs.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
            <div class="xl:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold text-gray-600" for="search">Search user, record or detail</label>
                <input id="search" name="search" type="text" maxlength="200" value="{{ request('search') }}" placeholder="Action, username, record ID..."
                       class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-gray-600" for="module">Module</label>
                <select id="module" name="module" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="">All modules</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-gray-600" for="action">Action</label>
                <select id="action" name="action" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-gray-600" for="status">Status</label>
                <select id="status" name="status" class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="xl:self-end">
                <button type="submit" class="w-full rounded-lg bg-[#101064] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#191989]">Apply Filters</button>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-gray-600" for="date_from">From</label>
                <input id="date_from" type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold text-gray-600" for="date_to">To</label>
                <input id="date_to" type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full rounded-lg border-gray-300 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
            </div>
        </form>
        @if($errors->any())
            <p class="mt-3 text-sm text-red-600">{{ $errors->first() }}</p>
        @endif
    </section>

    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-2 border-b border-gray-100 px-6 py-5">
            <div>
                <h2 class="text-lg font-bold text-[#101064]">Recorded Activities</h2>
                <p class="mt-1 text-xs text-gray-500">Only events recorded by the logging system appear here.</p>
            </div>
            <span class="rounded-full bg-[#F1F2FA] px-4 py-2 text-xs font-bold text-[#101064]">
                {{ number_format(method_exists($logs, 'total') ? $logs->total() : $logs->count()) }} matching logs
            </span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-gray-50 text-[11px] uppercase tracking-wider text-gray-500">
                    <tr>
                        <th class="px-5 py-4">Date & Time</th>
                        <th class="px-5 py-4">User</th>
                        <th class="px-5 py-4">Action</th>
                        <th class="px-5 py-4">Module</th>
                        <th class="px-5 py-4">Affected Record</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="align-top hover:bg-gray-50/70">
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="font-semibold text-gray-800">{{ $log->created_at?->format('M d, Y') ?? '—' }}</div>
                                <div class="text-xs text-gray-500">{{ $log->created_at?->format('h:i:s A') ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-semibold text-[#101064]">{{ $log->user?->name ?? 'System / Unknown' }}</div>
                                <div class="mt-0.5 text-xs text-gray-500">{{ $log->user?->username ?? '—' }}</div>
                            </td>
                            <td class="px-5 py-4 font-medium text-gray-800">{{ $log->action }}</td>
                            <td class="px-5 py-4"><span class="rounded-full bg-[#F1F2FA] px-3 py-1 text-xs font-semibold text-[#101064]">{{ $log->module }}</span></td>
                            <td class="px-5 py-4 text-xs text-gray-600">
                                @if($log->subject_type && $log->subject_id)
                                    <span class="font-semibold">{{ $log->subject_type }}</span>
                                    <span class="text-gray-400">#{{ $log->subject_id }}</span>
                                @else
                                    <span class="text-gray-400">Not specified</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                @if($log->status === 'Success')
                                    <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Success</span>
                                @elseif($log->status === 'Failed')
                                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Failed</span>
                                @else
                                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">{{ $log->status }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <details class="audit-detail group min-w-[170px]">
                                    <summary class="cursor-pointer select-none font-semibold text-[#101064] hover:text-[#B58812]">View details ▾</summary>
                                    <div class="mt-3 w-[310px] max-w-[50vw] space-y-2 rounded-xl border border-gray-200 bg-gray-50 p-3 text-xs leading-relaxed text-gray-700">
                                        <p><strong>Description:</strong> {{ $log->description ?: 'No description recorded.' }}</p>
                                        <p><strong>IP:</strong> {{ $log->ip_address ?: '—' }}</p>
                                        <p class="break-all"><strong>User agent:</strong> {{ $log->user_agent ?: '—' }}</p>
                                        @if(is_array($log->changes_before) && is_array($log->changes_after) && count($log->changes_after))
                                            <div class="border-t border-gray-200 pt-2">
                                                <p class="mb-2 font-semibold text-[#101064]">Changed Fields</p>
                                                @foreach($log->changes_after as $field => $newValue)
                                                    <div class="mb-2 rounded-md bg-white p-2">
                                                        <div class="font-semibold">{{ str_replace('_', ' ', ucfirst($field)) }}</div>
                                                        <div class="break-words text-red-600">Before: {{ is_scalar($log->changes_before[$field] ?? null) ? (string) ($log->changes_before[$field] ?? '—') : json_encode($log->changes_before[$field] ?? null) }}</div>
                                                        <div class="break-words text-green-700">After: {{ is_scalar($newValue) ? (string) $newValue : json_encode($newValue) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-gray-400">No field-by-field changes stored for this action.</p>
                                        @endif
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-sm text-gray-500">No activity logs match your filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($logs, 'hasPages') && $logs->hasPages())
            <div class="border-t border-gray-100 p-5">{{ $logs->links() }}</div>
        @endif
    </section>
</div>
</x-admin-layout>
