<x-admin-layout>
    @php
        $status = $event->status ?? 'Upcoming';
        $isDepartmentStaff = auth()->user()?->role?->role_name === 'Department Staff';
        $canEdit = $isDepartmentStaff && !in_array($status, ['Completed', 'Cancelled'], true);
        $canCancel = $isDepartmentStaff && !in_array($status, ['Completed', 'Cancelled'], true);

        $statusClasses = match ($status) {
            'Upcoming' => 'bg-amber-100 text-amber-800',
            'Ongoing' => 'bg-emerald-100 text-emerald-800',
            'Completed' => 'bg-slate-100 text-slate-700',
            'Cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <style>
        /* Same DYCI navy/photo hero as the other Event Management pages. */
        .dysign-event-hero {
            position: relative;
            isolation: isolate;
            background-image:
                linear-gradient(100deg, rgba(16, 16, 100, .97) 0%, rgba(16, 16, 100, .89) 55%, rgba(16, 16, 100, .72) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
        }

        /* Explicit CSS keeps the decorative rings visible even if Tailwind does not compile new utilities. */
        .dysign-event-rings {
            position: absolute;
            inset: 0;
            z-index: 1;
            overflow: hidden;
            pointer-events: none;
        }

        .dysign-event-rings span {
            position: absolute;
            display: block;
            border-style: solid;
            border-radius: 50%;
            box-sizing: border-box;
        }

        .dysign-event-rings .ring-outer {
            width: 440px;
            height: 440px;
            top: -178px;
            right: -46px;
            border-width: 2px;
            border-color: rgba(255, 255, 255, .44);
        }

        .dysign-event-rings .ring-middle {
            width: 330px;
            height: 330px;
            top: -118px;
            right: 9px;
            border-width: 2px;
            border-color: rgba(255, 255, 255, .32);
        }

        .dysign-event-rings .ring-inner {
            width: 220px;
            height: 220px;
            top: -56px;
            right: 64px;
            border-width: 2px;
            border-color: rgba(231, 199, 91, .42);
        }

        /*
         * Native dialog positioning: explicit CSS avoids missing Tailwind
         * arbitrary-value utilities or global layout styles pushing the
         * confirmation box to the top-left of the screen.
         */
        #cancel-event-dialog {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            right: auto !important;
            bottom: auto !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            width: min(440px, calc(100vw - 32px)) !important;
            max-width: calc(100vw - 32px) !important;
            max-height: calc(100vh - 32px) !important;
            overflow-y: auto;
            padding: 0 !important;
            border: 0 !important;
            border-radius: 24px !important;
            background: #fff !important;
            color: #334155;
            box-shadow: 0 24px 80px rgba(10, 15, 48, .25);
            z-index: 2147483647;
        }

        #cancel-event-dialog:not([open]) {
            display: none !important;
        }

        #cancel-event-dialog[open] {
            display: block !important;
        }

        #cancel-event-dialog::backdrop {
            background: rgba(8, 10, 46, .65);
            backdrop-filter: blur(4px);
        }

        @media (max-width: 640px) {
            .dysign-event-rings { opacity: .65; transform: translateX(125px) scale(.82); transform-origin: top right; }
        }
    </style>

    <div class="min-w-0 space-y-6">
        {{-- EVENT HERO --}}
        <section class="dysign-event-hero overflow-hidden rounded-[28px] text-white shadow-sm">
            {{-- Three clearly visible rings, layered above the campus photo and below the text. --}}
            <div class="dysign-event-rings" aria-hidden="true">
                <span class="ring-outer"></span>
                <span class="ring-middle"></span>
                <span class="ring-inner"></span>
            </div>

            <div class="absolute bottom-0 left-0 z-20 h-1 w-full bg-[#D4A017]"></div>

            <div class="relative flex flex-col gap-6 px-7 py-9 sm:px-9 lg:flex-row lg:items-start lg:justify-between lg:px-10 lg:py-10" style="z-index: 2;">
                <div class="min-w-0 max-w-4xl">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-[2px] w-8 bg-[#D4A017]"></span>
                        <p class="text-[11px] font-bold uppercase tracking-[0.25em] text-[#E7C75B]">
                            Event Management / Event Details
                        </p>
                    </div>

                    <h1 class="max-w-4xl break-words text-3xl font-bold tracking-tight sm:text-4xl">
                        {{ $event->event_name }}
                    </h1>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-bold text-[#101064]">
                            {{ $status }}
                        </span>
                        <span class="text-sm text-white/80">
                            {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('F d, Y') }}
                        </span>
                    </div>
                </div>

                <a href="{{ route('events.index') }}"
                   class="inline-flex shrink-0 items-center justify-center gap-2 self-start rounded-xl border border-white/25 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur-md transition hover:bg-white/20">
                    <span aria-hidden="true">←</span> Back to Events
                </a>
            </div>
        </section>

        {{-- ONE CLEAN EVENT INFORMATION PANEL --}}
        <section class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-7 py-6 sm:px-8">
                <h2 class="text-xl font-bold text-[#101064]">Event Information</h2>
                <p class="mt-1 text-xs text-slate-500">Official event details and schedule</p>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 px-7 py-7 sm:grid-cols-2 sm:px-8 xl:grid-cols-4">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.13em] text-slate-400">Event Date</p>
                    <p class="mt-3 text-sm font-semibold text-[#101064]">
                        {{ \Illuminate\Support\Carbon::parse($event->event_date)->format('F d, Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.13em] text-slate-400">Event Time</p>
                    <p class="mt-3 text-sm font-semibold text-[#101064]">
                        {{ \Illuminate\Support\Carbon::parse($event->start_time)->format('g:i A') }}
                        –
                        {{ \Illuminate\Support\Carbon::parse($event->end_time)->format('g:i A') }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.13em] text-slate-400">Venue</p>
                    <p class="mt-3 break-words text-sm font-semibold text-[#101064]">
                        {{ $event->location ?: 'Not specified' }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.13em] text-slate-400">Organizing Department</p>
                    <p class="mt-3 text-sm font-semibold text-[#101064]">
                        {{ $event->department?->department_name ?? 'Not assigned' }}
                    </p>
                </div>
            </div>

            <div class="border-t border-slate-100 px-7 py-7 sm:px-8">
                <div class="mb-4 flex items-center gap-3">
                    <div class="h-5 w-[3px] rounded-full bg-[#D4A017]"></div>
                    <h3 class="text-base font-bold text-[#101064]">About this Event</h3>
                </div>
                <p class="whitespace-pre-line text-sm leading-7 text-slate-600">{{ filled($event->description) ? $event->description : 'No description has been provided for this event.' }}</p>
            </div>

            @if ($isDepartmentStaff)
                <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 bg-slate-50/60 px-7 py-5 sm:px-8">
                    <p class="text-xs text-slate-500">Event #{{ $event->event_id }}</p>
                    <div class="flex flex-wrap items-center gap-3">
                        @if ($canCancel)
                            <button type="button"
                                    onclick="document.getElementById('cancel-event-dialog').showModal()"
                                    class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-5 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                                Cancel Event
                            </button>
                        @endif
                        @if ($canEdit)
                            <a href="{{ route('events.edit', $event->event_id) }}"
                               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#101064] px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-[#202080]">
                                Edit Event <span aria-hidden="true">→</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    </div>

    {{-- CANCEL CONFIRMATION: does not delete a record; controller marks it Cancelled. --}}
    @if ($canCancel)
        <dialog id="cancel-event-dialog"
                aria-labelledby="cancel-event-title"
                aria-describedby="cancel-event-description"
                class="dy-cancel-event-dialog">
            <div class="px-7 pb-4 pt-7">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path stroke-linecap="round" d="M12 7v6M12 17h.01"/>
                    </svg>
                </div>
                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#B68C25]">Confirmation Required</p>
                <h2 id="cancel-event-title" class="mt-2 text-2xl font-bold text-[#101064]">Cancel this event?</h2>
                <p id="cancel-event-description" class="mt-3 text-sm leading-6 text-slate-600">
                    Are you sure you want to cancel <strong>{{ $event->event_name }}</strong>?
                    The event will be marked as Cancelled.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 bg-slate-50 px-7 py-5">
                <button type="button"
                        onclick="document.getElementById('cancel-event-dialog').close()"
                        class="rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Go Back
                </button>
                <form action="{{ route('events.destroy', $event->event_id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700">
                        Yes, Cancel Event
                    </button>
                </form>
            </div>
        </dialog>
    @endif
</x-admin-layout>
