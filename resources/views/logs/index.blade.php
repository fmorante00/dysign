<x-admin-layout>
@php
    $sensitiveActions = $sensitiveActions ?? [];
    $priority = $priority ?? 'all';
    $sort = $sort ?? 'priority';
    $perPage = $perPage ?? 10;
    $activeFilters = request()->except(['page']);
    $quickViews = [
        ['value' => 'all', 'title' => 'All activity', 'caption' => 'Every recorded action'],
        ['value' => 'failed', 'title' => 'Failed actions', 'caption' => 'Review outcomes'],
        ['value' => 'sensitive', 'title' => 'Key changes', 'caption' => 'Access and records'],
        ['value' => 'routine', 'title' => 'Routine', 'caption' => 'Other successes'],
    ];
@endphp
<style>
    .dy-audit-campus-hero {
        background-image: linear-gradient(100deg, rgba(12,13,79,.97), rgba(16,16,100,.89) 54%, rgba(16,16,100,.74)), url('{{ asset('images/school.jpg') }}');
        background-size: cover;
        background-position: center;
    }
    .dy-audit-details summary { list-style: none; }
    .dy-audit-details summary::-webkit-details-marker { display: none; }
    .dy-audit-details[open] summary .dy-audit-chevron { transform: rotate(180deg); }
</style>

<div class="min-w-0 space-y-7 pb-10">
    <section class="dy-audit-campus-hero relative overflow-hidden rounded-[28px] px-6 py-9 text-white shadow-sm sm:px-9">
        <div class="pointer-events-none absolute -right-16 -top-24 h-80 w-80 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute -right-4 -top-12 h-60 w-60 rounded-full border border-white/10"></div>
        <div class="pointer-events-none absolute bottom-0 right-0 h-28 w-80 bg-gradient-to-l from-[#D4A017]/20 to-transparent"></div>
        <div class="pointer-events-none absolute bottom-0 left-0 h-1 w-full bg-[#D4A017]"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-6">
            <div class="max-w-2xl">
                <p class="text-[11px] font-bold uppercase tracking-[.24em] text-[#E7C75B]">System Management / Audit Trail</p>
                <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Activity Logs &amp; Audit Trail</h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-white/80">Review failed requests, important system changes, and the full history of recorded actions across DySign.</p>
                <div class="mt-5 flex flex-wrap gap-2 text-xs">
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 font-semibold text-white/90">Administrator access</span>
                    <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1.5 font-semibold text-white/90">Recorded activities only</span>
                </div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/10 px-5 py-4 backdrop-blur-sm">
                <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#E7C75B]">Default View</p>
                <p class="mt-1 text-lg font-bold">Priority First</p>
                <p class="mt-1 text-xs text-white/75">Switch to Latest First below</p>
            </div>
        </div>
    </section>

    <section aria-labelledby="audit-overview-heading">
        <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.25em] text-[#BD8C00]">Audit Overview</p>
                <h2 id="audit-overview-heading" class="mt-1 text-lg font-bold text-[#101064]">Institutional Activity Snapshot</h2>
            </div>
            <p class="text-xs text-slate-500">All-time counts · not affected by filters</p>
        </div>
        <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
            @foreach([
                ['name'=>'Failed Actions', 'value'=>$statistics['failed'], 'color'=>'text-red-700', 'bg'=>'bg-red-50', 'caption'=>'Operations to review', 'mark'=>'!'],
                ['name'=>'Key Changes', 'value'=>$statistics['sensitive'], 'color'=>'text-[#101064]', 'bg'=>'bg-indigo-50', 'caption'=>'Successful high-impact changes', 'mark'=>'◆'],
                ['name'=>'Logged Today', 'value'=>$statistics['today'], 'color'=>'text-[#AA7900]', 'bg'=>'bg-amber-50', 'caption'=>'Recorded during the current day', 'mark'=>'◷'],
                ['name'=>'All Activities', 'value'=>$statistics['total'], 'color'=>'text-[#101064]', 'bg'=>'bg-slate-100', 'caption'=>'Complete audit history', 'mark'=>'≡']
            ] as $metric)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ $metric['name'] }}</p>
                        <span aria-hidden="true" class="flex h-9 w-9 items-center justify-center rounded-xl {{ $metric['bg'] }} {{ $metric['color'] }} text-lg font-bold">{{ $metric['mark'] }}</span>
                    </div>
                    <p class="mt-2 text-3xl font-extrabold tabular-nums {{ $metric['color'] }}">{{ number_format($metric['value']) }}</p>
                    <div class="mt-3 border-t border-slate-100 pt-2.5 text-xs text-slate-500">{{ $metric['caption'] }}</div>
                </article>
            @endforeach
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="audit-review-heading">
        <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 p-5 sm:px-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.23em] text-[#BD8C00]">Review First</p>
                <h2 id="audit-review-heading" class="mt-1 text-lg font-bold text-[#101064]">Priority Activity</h2>
                <p class="mt-1 text-xs text-slate-500">Failed actions and key changes matching your current filters. Priority does not indicate a confirmed security incident.</p>
            </div>
            <span class="rounded-full bg-[#F1F2FA] px-4 py-2 text-xs font-bold text-[#101064]">{{ number_format($reviewCount) }} priority matches</span>
        </div>
        <div class="grid gap-3 p-4 md:grid-cols-2 lg:grid-cols-4 sm:p-5">
            @forelse($reviewLogs as $log)
                @php $failed = $log->status === 'Failed'; @endphp
                <article class="min-w-0 rounded-2xl border {{ $failed ? 'border-red-200 bg-red-50/40' : 'border-slate-200 bg-slate-50' }} p-4">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $failed ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800' }}">
                            {{ $failed ? 'Failed action' : 'Key change' }}
                        </span>
                        <span class="text-[10px] text-slate-500">{{ $log->created_at?->format('M j, Y') ?? '—' }}</span>
                    </div>
                    <p class="mt-3 break-words text-sm font-bold leading-5 text-[#101064]">{{ $log->action ?: 'Unnamed action' }}</p>
                    <p class="mt-2 truncate text-xs text-slate-600" title="{{ $log->user?->name ?? 'System / Unknown' }}">{{ $log->user?->name ?? 'System / Unknown' }}</p>
                    <div class="mt-3 flex items-center justify-between gap-2 border-t border-slate-200 pt-3 text-[11px]">
                        <span class="truncate text-slate-600">{{ $log->module ?: 'System' }}</span>
                        <span class="shrink-0 font-semibold text-[#101064]">{{ $log->created_at?->format('h:i A') ?? '' }}</span>
                    </div>
                </article>
            @empty
                <div class="col-span-full rounded-xl border border-dashed border-slate-200 bg-slate-50 p-8 text-center text-sm text-slate-500">No failed or key-change records match your current filters.</div>
            @endforelse
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="audit-filter-heading">
        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.23em] text-[#BD8C00]">Activity Controls</p>
                <h2 id="audit-filter-heading" class="mt-1 text-lg font-bold text-[#101064]">Explore Audit History</h2>
                <p class="mt-1 text-xs text-slate-500">Find a user, module, affected record, or action from the recorded audit entries.</p>
            </div>
            <a href="{{ route('logs.index') }}" class="rounded-lg border border-[#D4A017] px-4 py-2 text-xs font-bold text-[#101064] hover:bg-amber-50">Clear All Filters</a>
        </div>
        <div class="grid grid-cols-2 gap-2 px-5 pt-5 md:grid-cols-4 sm:px-6">
            @foreach($quickViews as $quickView)
                <a href="{{ route('logs.index', array_merge(request()->except(['page', 'priority']), $quickView['value'] === 'all' ? [] : ['priority' => $quickView['value']])) }}"
                   class="rounded-xl border px-3 py-3 transition {{ $priority === $quickView['value'] ? 'border-[#101064] bg-[#101064] text-white shadow-sm' : 'border-slate-200 bg-white text-[#101064] hover:bg-slate-50' }}">
                    <span class="block text-xs font-bold">{{ $quickView['title'] }}</span>
                    <span class="mt-1 block text-[10px] {{ $priority === $quickView['value'] ? 'text-white/70' : 'text-slate-500' }}">{{ $quickView['caption'] }}</span>
                </a>
            @endforeach
        </div>
        <form method="GET" action="{{ route('logs.index') }}" class="grid grid-cols-1 gap-4 p-5 sm:grid-cols-2 sm:px-6 xl:grid-cols-4">
            <input type="hidden" name="priority" value="{{ $priority }}">
            <div class="xl:col-span-2">
                <label class="mb-1.5 block text-xs font-semibold text-slate-600" for="audit-search">Search user, action, or record</label>
                <input id="audit-search" name="search" type="search" maxlength="200" value="{{ request('search') }}" placeholder="Name, username, module, record ID..." class="w-full rounded-xl border-slate-200 px-3.5 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
            </div>
            <div>
                <label for="audit-module" class="mb-1.5 block text-xs font-semibold text-slate-600">Module</label>
                <select id="audit-module" name="module" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="">All modules</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="audit-action" class="mb-1.5 block text-xs font-semibold text-slate-600">Action</label>
                <select id="audit-action" name="action" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="">All actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" @selected(request('action') === $action)>{{ $action }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="audit-status" class="mb-1.5 block text-xs font-semibold text-slate-600">Status</label>
                <select id="audit-status" name="status" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="">All statuses</option>
                    <option value="Failed" @selected(request('status') === 'Failed')>Failed</option>
                    <option value="Success" @selected(request('status') === 'Success')>Success</option>
                </select>
            </div>
            <div>
                <label for="audit-date-from" class="mb-1.5 block text-xs font-semibold text-slate-600">Date From</label>
                <input id="audit-date-from" type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
            </div>
            <div>
                <label for="audit-date-to" class="mb-1.5 block text-xs font-semibold text-slate-600">Date To</label>
                <input id="audit-date-to" type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
            </div>
            <div>
                <label for="audit-sort" class="mb-1.5 block text-xs font-semibold text-slate-600">Order</label>
                <select id="audit-sort" name="sort" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    <option value="priority" @selected($sort === 'priority')>Priority First</option>
                    <option value="latest" @selected($sort === 'latest')>Latest First</option>
                </select>
            </div>
            <div>
                <label for="audit-per-page" class="mb-1.5 block text-xs font-semibold text-slate-600">Rows per page</label>
                <select id="audit-per-page" name="per_page" class="w-full rounded-xl border-slate-200 px-3 py-2.5 text-sm focus:border-[#D4A017] focus:ring-[#D4A017]">
                    @foreach([10,25,50] as $size)
                        <option value="{{ $size }}" @selected((int) $perPage === $size)>{{ $size }} records</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end sm:col-span-2 xl:col-span-4">
                <button type="submit" class="w-full rounded-xl bg-[#101064] px-6 py-2.5 text-sm font-bold text-white transition hover:bg-[#0d0d50] sm:ml-auto sm:w-auto">Apply Filters</button>
            </div>
        </form>
        @if($errors->any())
            <p class="mx-6 mb-5 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</p>
        @endif
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="audit-history-heading">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-5 py-5 sm:px-6">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.23em] text-[#BD8C00]">Complete Audit Registry</p>
                <h2 id="audit-history-heading" class="mt-1 text-lg font-bold text-[#101064]">Recorded Activities</h2>
                <p class="mt-1 text-xs text-slate-500">{{ $sort === 'priority' ? 'Failed, key changes, then routine actions — newest within each group.' : 'Newest records first, regardless of priority.' }}</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-[#101064]">{{ number_format($logs->total()) }} matching records</p>
                <p class="mt-1 text-[11px] text-slate-500">Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }}</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-slate-50 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th scope="col" class="px-5 py-4">Priority</th>
                        <th scope="col" class="px-5 py-4">Date &amp; Time</th>
                        <th scope="col" class="px-5 py-4">User</th>
                        <th scope="col" class="px-5 py-4">Recorded Action</th>
                        <th scope="col" class="px-5 py-4">Module</th>
                        <th scope="col" class="px-5 py-4">Affected Record</th>
                        <th scope="col" class="px-5 py-4">Outcome</th>
                        <th scope="col" class="px-5 py-4">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        @php
                            $failed = $log->status === 'Failed';
                            $sensitive = !$failed && in_array($log->action, $sensitiveActions, true);
                        @endphp
                        <tr class="align-top transition-colors hover:bg-slate-50/80">
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-[11px] font-bold {{ $failed ? 'bg-red-50 text-red-700' : ($sensitive ? 'bg-amber-50 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                    <span aria-hidden="true">{{ $failed ? '!' : ($sensitive ? '◆' : '·') }}</span>
                                    {{ $failed ? 'Review' : ($sensitive ? 'Key change' : 'Routine') }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <div class="font-semibold text-slate-800">{{ $log->created_at?->format('M d, Y') ?? '—' }}</div>
                                <div class="mt-1 text-xs text-slate-500">{{ $log->created_at?->format('h:i:s A') ?? '—' }}</div>
                            </td>
                            <td class="max-w-[180px] px-5 py-4">
                                <div class="break-words font-bold text-[#101064]">{{ $log->user?->name ?? 'System / Unknown' }}</div>
                                <div class="mt-1 break-all text-xs text-slate-500">{{ $log->user?->username ?? '—' }}</div>
                            </td>
                            <td class="max-w-[240px] px-5 py-4 font-semibold leading-6 text-slate-800">{{ $log->action ?: '—' }}</td>
                            <td class="px-5 py-4"><span class="rounded-full bg-[#F1F2FA] px-3 py-1 text-xs font-semibold text-[#101064]">{{ $log->module ?: 'System' }}</span></td>
                            <td class="px-5 py-4 text-xs text-slate-600">
                                @if($log->subject_type && $log->subject_id)
                                    <span class="font-semibold">{{ $log->subject_type }}</span>
                                    <span class="text-slate-400">#{{ $log->subject_id }}</span>
                                @else
                                    <span class="text-slate-400">Not specified</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $failed ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">{{ $log->status }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <details class="dy-audit-details min-w-[155px]">
                                    <summary class="flex cursor-pointer select-none items-center gap-2 text-xs font-bold text-[#101064] hover:text-[#B58812]">
                                        View details <span aria-hidden="true" class="dy-audit-chevron transition-transform">⌄</span>
                                    </summary>
                                    <div class="mt-3 w-[320px] max-w-[65vw] space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3 text-xs leading-5 text-slate-700">
                                        <p class="break-words"><strong>Description:</strong> {{ $log->description ?: 'No description recorded.' }}</p>
                                        <p class="break-all"><strong>IP:</strong> {{ $log->ip_address ?: '—' }}</p>
                                        <p class="break-all"><strong>User agent:</strong> {{ $log->user_agent ?: '—' }}</p>
                                        @if(is_array($log->changes_after) && count($log->changes_after))
                                            <div class="border-t border-slate-200 pt-2">
                                                <p class="mb-2 font-bold text-[#101064]">Changed Fields</p>
                                                @foreach($log->changes_after as $field => $newValue)
                                                    <div class="mb-2 rounded-lg border border-slate-100 bg-white p-2">
                                                        <div class="font-semibold">{{ str_replace('_', ' ', ucfirst($field)) }}</div>
                                                        <div class="break-words text-red-700">Before: {{ is_scalar($log->changes_before[$field] ?? null) ? (string) ($log->changes_before[$field] ?? '—') : json_encode($log->changes_before[$field] ?? null) }}</div>
                                                        <div class="break-words text-green-700">After: {{ is_scalar($newValue) ? (string) $newValue : json_encode($newValue) }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-slate-500">No field-by-field changes stored for this action.</p>
                                        @endif
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="px-6 py-16 text-center text-sm text-slate-500">No activities match the selected search and filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 px-5 py-4 text-xs text-slate-500 sm:px-6">
            <p>Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ number_format($logs->total()) }} matching records</p>
            <p>Full audit details remain available using “View details”.</p>
        </div>
        @if($logs->hasPages())
            <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $logs->links() }}</div>
        @endif
    </section>
</div>
</x-admin-layout>
