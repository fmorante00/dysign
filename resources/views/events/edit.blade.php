<x-admin-layout>
    @php
        $departmentName = $event->department?->department_name
            ?? auth()->user()?->department?->department_name
            ?? 'No department assigned';

        $eventDate = old('event_date', $event->event_date
            ? \Illuminate\Support\Carbon::parse($event->event_date)->format('Y-m-d')
            : '');
        $startTime = old('start_time', $event->start_time
            ? \Illuminate\Support\Carbon::parse($event->start_time)->format('H:i')
            : '');
        $endTime = old('end_time', $event->end_time
            ? \Illuminate\Support\Carbon::parse($event->end_time)->format('H:i')
            : '');

        $statusClasses = match ($event->status) {
            'Upcoming' => 'bg-amber-50 text-amber-800',
            'Ongoing' => 'bg-emerald-50 text-emerald-800',
            'Completed' => 'bg-slate-100 text-slate-700',
            'Cancelled' => 'bg-red-50 text-red-700',
            default => 'bg-slate-100 text-slate-700',
        };
    @endphp

    <style>
        /* Keep the styling consistent with DySign's redesigned Create Event page. */
        .dy-edit-hero {
            background-image:
                linear-gradient(100deg, rgba(16,16,100,.94), rgba(16,16,100,.86) 52%, rgba(16,16,100,.63)),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        /* Explicit positions and higher-contrast borders so the circles stay visible. */
        .dy-edit-ring {
            position: absolute;
            border: 2px solid rgba(255,255,255,.27);
            border-radius: 50%;
            pointer-events: none;
        }
        .dy-edit-field {
            display: block;
            width: 100%;
            border: 1px solid #dbe1eb;
            background: #f9fafc;
            border-radius: .85rem;
            padding: .83rem 1rem;
            font-size: .9rem;
            color: #18204a;
            transition: border-color .2s, background .2s;
        }
        .dy-edit-field:focus {
            border-color: #101064;
            outline: 3px solid rgba(16,16,100,.10);
            outline-offset: 0;
            background: white;
        }
        .dy-edit-field::placeholder { color: #939bb0; }
        .dy-edit-label {
            display: block;
            margin-bottom: .55rem;
            color: #222851;
            font-size: .8rem;
            font-weight: 700;
        }
        .dy-edit-card {
            background: white;
            border: 1px solid #e7eaf1;
            border-radius: 1.3rem;
            box-shadow: 0 5px 25px rgba(16,16,100,.035);
        }
        /* Correctly center the native dialog in the viewport.
           The inset shorthand must not reset top/left to auto. */
        dialog#dyEditConfirmDialog {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            right: auto !important;
            bottom: auto !important;
            inset: 50% auto auto 50% !important;
            transform: translate(-50%, -50%) !important;
            margin: 0 !important;
            width: min(520px, calc(100vw - 32px)) !important;
            max-width: none !important;
            max-height: calc(100dvh - 32px);
            overflow-y: auto;
            box-sizing: border-box;
        }
        dialog#dyEditConfirmDialog::backdrop {
            background: rgba(8,10,48,.68);
            backdrop-filter: blur(4px);
        }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-10">
        {{-- Hero: same full-width photo and university identity as Create Event. --}}
        <section class="dy-edit-hero relative isolate overflow-hidden rounded-[1.65rem] bg-[#101064] px-6 py-8 text-white shadow-lg sm:px-9 sm:py-10">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
                <div class="dy-edit-ring" style="width:380px;height:380px;top:-155px;right:-25px"></div>
                <div class="dy-edit-ring" style="width:290px;height:290px;top:-108px;right:20px"></div>
                <div class="dy-edit-ring" style="width:200px;height:200px;top:-63px;right:65px"></div>
            </div>
            <div class="absolute inset-x-0 bottom-0 h-1 bg-[#D4A017]"></div>

            <div class="relative z-10 flex flex-wrap items-start justify-between gap-5">
                <div class="min-w-0">
                    <div class="mb-4 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[.23em] text-[#E7C75B]">
                        <span class="h-px w-8 bg-[#D4A017]"></span> Department Event Management
                    </div>
                    <h1 class="break-words text-3xl font-bold tracking-tight sm:text-4xl">
                        Edit Event<span class="text-[#E7C75B]">.</span>
                    </h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80">
                        Update the event details and review your changes before saving.
                    </p>
                    <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-white/75">
                        <span class="max-w-[450px] truncate">{{ $event->event_name }}</span>
                        <span aria-hidden="true">•</span>
                        <span>Event #{{ $event->event_id }}</span>
                        <span aria-hidden="true">•</span>
                        <span>{{ $event->status }}</span>
                    </div>
                </div>
                <a href="{{ route('events.show', $event->event_id) }}"
                   class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                    <span aria-hidden="true">&#8592;</span> Back to Event
                </a>
            </div>
        </section>

        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-bold">Changes could not be saved. Please check:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($event->status === 'Completed')
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-3 text-xs leading-6 text-amber-900">
                <strong>Completed event:</strong> Changes to its schedule or details may affect existing attendance records and reports. Review carefully before saving.
            </div>
        @endif

        <form id="dyEditEventForm" method="POST" action="{{ route('events.update', $event->event_id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- 01: General information --}}
            <section class="dy-edit-card overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#101064]/[.07] text-xl text-[#101064]" aria-hidden="true">01</div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#B68D24]">Event Details</p>
                            <h2 class="mt-0.5 text-lg font-bold text-[#101064]">General Information</h2>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:px-8 md:grid-cols-2">
                    <div>
                        <label for="event_name" class="dy-edit-label">Event Name <span class="text-red-500">*</span></label>
                        <input id="event_name" name="event_name" type="text" class="dy-edit-field" value="{{ old('event_name', $event->event_name) }}" maxlength="255" required autocomplete="off">
                        @error('event_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="location" class="dy-edit-label">Event Venue <span class="text-red-500">*</span></label>
                        <input id="location" name="location" type="text" class="dy-edit-field" value="{{ old('location', $event->location) }}" maxlength="255" required autocomplete="off">
                        @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <p class="dy-edit-label">Organizing Department</p>
                        <div class="flex min-h-[54px] items-center gap-3 rounded-xl border border-[#E8E4D4] bg-[#FCFAF3] px-4 py-3">
                            <span class="text-sm font-semibold text-[#101064]">{{ $departmentName }}</span>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">Department is fixed for this event.</p>
                    </div>
                    <div>
                        <p class="dy-edit-label">Current Event Status</p>
                        <div class="flex min-h-[54px] items-center rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses }}">{{ $event->status }}</span>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-500">Status is managed automatically from the event schedule.</p>
                    </div>
                </div>
            </section>

            {{-- 02: Schedule --}}
            <section class="dy-edit-card overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#101064]/[.07] text-xl text-[#101064]" aria-hidden="true">02</div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#B68D24]">Schedule</p>
                            <h2 class="mt-0.5 text-lg font-bold text-[#101064]">Date &amp; Time</h2>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:px-8 md:grid-cols-3">
                    <div>
                        <label for="event_date" class="dy-edit-label">Event Date <span class="text-red-500">*</span></label>
                        <input id="event_date" name="event_date" type="date" class="dy-edit-field" value="{{ $eventDate }}" required>
                        @error('event_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="start_time" class="dy-edit-label">Start Time <span class="text-red-500">*</span></label>
                        <input id="start_time" name="start_time" type="time" class="dy-edit-field" value="{{ $startTime }}" required>
                        @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_time" class="dy-edit-label">End Time <span class="text-red-500">*</span></label>
                        <input id="end_time" name="end_time" type="time" class="dy-edit-field" value="{{ $endTime }}" required>
                        @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <p id="dyEditScheduleError" class="hidden text-xs font-semibold text-red-600 md:col-span-3" role="alert"></p>
                    <p class="-mt-2 text-xs leading-6 text-slate-500 md:col-span-3">End time must be later than start time. All changes are validated by Laravel when you save.</p>
                </div>
            </section>

            {{-- 03: Description --}}
            <section class="dy-edit-card overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#101064]/[.07] text-xl text-[#101064]" aria-hidden="true">03</div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#B68D24]">Additional Information</p>
                            <h2 class="mt-0.5 text-lg font-bold text-[#101064]">Description <span class="text-xs font-normal text-slate-400">(Optional)</span></h2>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-6 sm:px-8">
                    <label for="description" class="dy-edit-label">Event Description</label>
                    <textarea id="description" name="description" rows="5" class="dy-edit-field resize-y leading-6" placeholder="Describe the event, its purpose, and any important instructions for participants...">{{ old('description', $event->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            {{-- Primary actions --}}
            <div class="dy-edit-card flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <div>
                    <p class="text-sm font-bold text-[#101064]">Ready to save your changes?</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">A confirmation window lets you review the updated event details first.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('events.show', $event->event_id) }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancel</a>
                    <button id="dyEditReviewButton" type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#202080] focus:outline-none focus:ring-4 focus:ring-[#D4A017]/25">
                        Review &amp; Save Changes <span aria-hidden="true">&#8594;</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Confirmation dialog is outside the form to avoid nested forms. --}}
    <dialog id="dyEditConfirmDialog" aria-labelledby="dyEditConfirmTitle" aria-describedby="dyEditConfirmDescription"
            class="fixed inset-0 m-auto w-[min(92vw,520px)] max-w-none overflow-hidden rounded-[24px] border-0 bg-white p-0 text-slate-800 shadow-2xl">
        <div class="border-b border-slate-100 bg-[#FBFBFE] px-6 py-6 sm:px-8">
            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF5DB] text-2xl text-[#A07912]" aria-hidden="true">&#10003;</div>
            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#B68D24]">Confirmation Required</p>
            <h2 id="dyEditConfirmTitle" class="mt-1 text-xl font-bold text-[#101064]">Save Event Changes?</h2>
            <p id="dyEditConfirmDescription" class="mt-2 text-sm leading-6 text-slate-600">Please check the updated information. Nothing will be saved until you confirm.</p>
        </div>
        <dl class="grid grid-cols-1 gap-4 px-6 py-6 text-sm sm:grid-cols-2 sm:px-8">
            <div class="sm:col-span-2"><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Event</dt><dd id="dyEditConfirmName" class="mt-1 break-words font-bold text-[#101064]"></dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Venue</dt><dd id="dyEditConfirmVenue" class="mt-1 break-words font-semibold text-slate-700"></dd></div>
            <div><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Date</dt><dd id="dyEditConfirmDate" class="mt-1 font-semibold text-slate-700"></dd></div>
            <div><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Time</dt><dd id="dyEditConfirmTime" class="mt-1 font-semibold text-slate-700"></dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-bold uppercase tracking-wide text-slate-400">Department</dt><dd class="mt-1 font-semibold text-slate-700">{{ $departmentName }}</dd></div>
        </dl>
        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">
            <button id="dyEditKeepEditing" type="button" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Keep Editing</button>
            <button id="dyEditConfirmSave" type="button" class="rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#202080] focus:outline-none focus:ring-4 focus:ring-[#D4A017]/25">Yes, Save Changes</button>
        </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('dyEditEventForm');
            const dialog = document.getElementById('dyEditConfirmDialog');
            const reviewButton = document.getElementById('dyEditReviewButton');
            const keepEditingButton = document.getElementById('dyEditKeepEditing');
            const confirmButton = document.getElementById('dyEditConfirmSave');
            const start = document.getElementById('start_time');
            const end = document.getElementById('end_time');
            const scheduleError = document.getElementById('dyEditScheduleError');
            let approved = false;
            let submitting = false;
            let proceeding = false;

            function validSchedule() {
                const invalid = Boolean(start.value && end.value && end.value <= start.value);
                scheduleError.classList.toggle('hidden', !invalid);
                scheduleError.textContent = invalid ? 'End time must be later than the start time.' : '';
                if (invalid) end.focus();
                return !invalid;
            }

            form.addEventListener('input', function () {
                approved = false;
                scheduleError.classList.add('hidden');
            });

            form.addEventListener('submit', function (event) {
                if (submitting) {
                    event.preventDefault();
                    return;
                }
                if (!approved) {
                    event.preventDefault();
                    if (!form.reportValidity() || !validSchedule()) return;
                    document.getElementById('dyEditConfirmName').textContent = document.getElementById('event_name').value.trim();
                    document.getElementById('dyEditConfirmVenue').textContent = document.getElementById('location').value.trim();
                    document.getElementById('dyEditConfirmDate').textContent = document.getElementById('event_date').value;
                    document.getElementById('dyEditConfirmTime').textContent = start.value + ' - ' + end.value;
                    dialog.showModal();
                    return;
                }
                submitting = true;
                reviewButton.disabled = true;
                confirmButton.disabled = true;
                reviewButton.textContent = 'Saving Changes...';
            });

            keepEditingButton.addEventListener('click', function () {
                approved = false;
                proceeding = false;
                dialog.close();
                reviewButton.focus();
            });

            confirmButton.addEventListener('click', function () {
                if (submitting || !validSchedule() || !form.reportValidity()) return;
                approved = true;
                proceeding = true;
                dialog.close();
                form.requestSubmit();
            });

            dialog.addEventListener('close', function () {
                if (!submitting && !proceeding) approved = false;
            });
        });
    </script>
</x-admin-layout>
