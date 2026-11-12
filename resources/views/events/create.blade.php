<x-admin-layout>
    @php
        $departmentName = auth()->user()?->department?->department_name ?? 'No department assigned';
    @endphp

    <style>
        [hidden] { display: none !important; }
        .dy-field:focus { border-color: #101064; outline: 3px solid rgba(16, 16, 100, .10); outline-offset: 0; }
        .dy-field { width: 100%; border: 1px solid #dbe1eb; background: #f9fafc; border-radius: .85rem; padding: .83rem 1rem; font-size: .9rem; color: #18204a; transition: border-color .2s, background .2s; }
        .dy-field:focus { background: white; }
        .dy-field::placeholder { color: #939bb0; }
        .dy-label { display: block; margin-bottom: .55rem; color: #222851; font-size: .8rem; font-weight: 700; }
        .dy-help { margin-top: .45rem; color: #8991a2; font-size: .75rem; line-height: 1.5; }
        .dy-panel { background: white; border: 1px solid #e7eaf1; border-radius: 1.3rem; box-shadow: 0 5px 25px rgba(16, 16, 100, .035); }
        /* Match the photo-backed hero on DySign's other Event pages. */
        .event-create-hero {
            background-image:
                linear-gradient(100deg, rgba(16,16,100,.94) 0%, rgba(16,16,100,.86) 52%, rgba(16,16,100,.63) 100%),
                url('{{ asset('images/school.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

    <div class="w-full min-w-0 space-y-6 pb-10">
        {{-- University-styled header --}}
        <section class="event-create-hero relative isolate overflow-hidden rounded-[1.65rem] bg-[#101064] px-6 py-8 text-white shadow-lg sm:px-9 sm:py-10">
            <div aria-hidden="true" class="pointer-events-none absolute -right-20 -top-52 h-[390px] w-[390px] rounded-full border border-white/15"></div>
            <div aria-hidden="true" class="pointer-events-none absolute -right-6 -top-40 h-[320px] w-[320px] rounded-full border border-white/10"></div>
            <div class="absolute inset-x-0 bottom-0 h-1 bg-[#D4A017]"></div>

            <div class="relative flex flex-wrap items-start justify-between gap-5">
                <div>
                    <div class="mb-4 flex items-center gap-2 text-[11px] font-bold uppercase tracking-[.23em] text-[#E7C75B]">
                        <span class="h-px w-8 bg-[#D4A017]"></span> Department Event Management
                    </div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Create New Event<span class="text-[#E7C75B]">.</span></h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/75">Provide the event details and schedule. Review everything before confirming the new record.</p>
                </div>
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                    <span aria-hidden="true">&#8592;</span> Back to Events
                </a>
            </div>
        </section>

        {{-- Server-side validation messages are always preserved --}}
        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
                <p class="font-bold">Please review the following information:</p>
                <ul class="mt-2 list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="createEventForm" method="POST" action="{{ route('events.store') }}" class="space-y-6">
            @csrf

            {{-- Event information --}}
            <section class="dy-panel overflow-hidden">
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
                        <label class="dy-label" for="event_name">Event Name <span class="text-red-500">*</span></label>
                        <input id="event_name" name="event_name" type="text" class="dy-field" value="{{ old('event_name') }}" maxlength="255" placeholder="e.g. CCS General Assembly" required autocomplete="off">
                        @error('event_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="dy-label" for="location">Event Venue <span class="text-red-500">*</span></label>
                        <input id="location" name="location" type="text" class="dy-field" value="{{ old('location') }}" maxlength="255" placeholder="e.g. DYCI Auditorium" required autocomplete="off">
                        @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <p class="dy-label">Organizing Department</p>
                        <div class="flex flex-wrap items-center gap-3 rounded-xl border border-[#E8E4D4] bg-[#FCFAF3] px-4 py-3.5">
                            <span aria-hidden="true" class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#D4A017]/15 text-[#8B680E]">&#127979;</span>
                            <div>
                                <p class="text-sm font-semibold text-[#101064]">{{ $departmentName }}</p>
                                <p class="mt-0.5 text-xs text-slate-500">Automatically assigned from your Department Staff account.</p>
                            </div>
                        </div>
                        {{-- Department ID is intentionally NOT submitted from the browser. --}}
                    </div>
                </div>
            </section>

            {{-- Schedule --}}
            <section class="dy-panel overflow-hidden">
                <div class="border-b border-slate-100 px-6 py-5 sm:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#101064]/[.07] text-xl text-[#101064]" aria-hidden="true">02</div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[.2em] text-[#B68D24]">Schedule</p>
                            <h2 class="mt-0.5 text-lg font-bold text-[#101064]">Date & Time</h2>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 px-6 py-6 sm:px-8 md:grid-cols-3">
                    <div>
                        <label class="dy-label" for="event_date">Event Date <span class="text-red-500">*</span></label>
                        <input id="event_date" name="event_date" type="date" class="dy-field" value="{{ old('event_date') }}" min="{{ now()->toDateString() }}" required>
                        @error('event_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="dy-label" for="start_time">Start Time <span class="text-red-500">*</span></label>
                        <input id="start_time" name="start_time" type="time" class="dy-field" value="{{ old('start_time') }}" required>
                        @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="dy-label" for="end_time">End Time <span class="text-red-500">*</span></label>
                        <input id="end_time" name="end_time" type="time" class="dy-field" value="{{ old('end_time') }}" required>
                        @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <p class="md:col-span-3 -mt-2 text-xs leading-6 text-slate-500">Start and end times must be on the selected date, and the end time must be later than the start time. Final checks are performed by Laravel.</p>
                </div>
            </section>

            {{-- Description --}}
            <section class="dy-panel overflow-hidden">
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
                    <label class="dy-label" for="description">Event Description</label>
                    <textarea id="description" name="description" rows="5" class="dy-field resize-y leading-6" placeholder="Describe the event, its purpose, and any important instructions for participants...">{{ old('description') }}</textarea>
                    <p class="dy-help">Eligibility rules and attendance personnel can be configured after creating the event.</p>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </section>

            <div class="dy-panel flex flex-col gap-4 px-6 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                <div>
                    <p class="text-sm font-bold text-[#101064]">Ready to create your event?</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">You'll review the details in a confirmation window before anything is saved.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('events.index') }}" class="rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">Cancel</a>
                    <button id="reviewEventButton" type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#202080] focus:outline-none focus:ring-4 focus:ring-[#D4A017]/25">
                        Review & Create Event <span aria-hidden="true">&#8594;</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Confirmation modal: outside the form so no nested forms --}}
    <div id="eventConfirmModal" hidden class="fixed inset-0 z-[200] flex items-center justify-center bg-[#0A0B35]/70 px-4 py-6 backdrop-blur-sm" role="presentation">
        <div id="eventConfirmDialog" role="dialog" aria-modal="true" aria-labelledby="eventConfirmTitle" aria-describedby="eventConfirmDescription" class="w-full max-w-lg overflow-hidden rounded-[1.5rem] bg-white shadow-[0_28px_100px_rgba(4,8,45,.35)]">
            <div class="border-b border-slate-100 bg-[#FBFBFE] px-6 py-6 sm:px-8">
                <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-[#FFF5DB] text-2xl text-[#A07912]" aria-hidden="true">&#10003;</div>
                <h2 id="eventConfirmTitle" class="text-xl font-bold text-[#101064]">Confirm Event Creation</h2>
                <p id="eventConfirmDescription" class="mt-2 text-sm leading-6 text-slate-600">Please check the information below. The new event will be saved only after you confirm.</p>
            </div>
            <dl class="grid grid-cols-1 gap-4 px-6 py-6 text-sm sm:grid-cols-2 sm:px-8">
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Event</dt><dd id="confirmName" class="mt-1 break-words font-bold text-[#101064]"></dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Venue</dt><dd id="confirmLocation" class="mt-1 break-words font-semibold text-slate-700"></dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Date</dt><dd id="confirmDate" class="mt-1 font-semibold text-slate-700"></dd></div>
                <div><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Time</dt><dd id="confirmTime" class="mt-1 font-semibold text-slate-700"></dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-400">Department</dt><dd class="mt-1 font-semibold text-slate-700">{{ $departmentName }}</dd></div>
            </dl>
            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50 px-6 py-5 sm:flex-row sm:justify-end sm:px-8">
                <button id="cancelEventConfirm" type="button" class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Go Back & Edit</button>
                <button id="submitEventConfirm" type="button" class="rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#202080] focus:outline-none focus:ring-4 focus:ring-[#D4A017]/25">Yes, Create Event</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('createEventForm');
            const modal = document.getElementById('eventConfirmModal');
            const cancel = document.getElementById('cancelEventConfirm');
            const confirm = document.getElementById('submitEventConfirm');
            const reviewButton = document.getElementById('reviewEventButton');
            const dateInput = document.getElementById('event_date');
            const startInput = document.getElementById('start_time');
            const endInput = document.getElementById('end_time');
            let approved = false;
            let submitting = false;

            // Clear custom messages when users edit the schedule.
            [dateInput, startInput, endInput].forEach(input => {
                input.addEventListener('input', () => {
                    startInput.setCustomValidity('');
                    endInput.setCustomValidity('');
                });
            });

            function validateSchedule() {
                startInput.setCustomValidity('');
                endInput.setCustomValidity('');

                if (startInput.value && endInput.value && endInput.value <= startInput.value) {
                    endInput.setCustomValidity('End time must be later than start time.');
                    endInput.reportValidity();
                    return false;
                }

                if (dateInput.value && startInput.value) {
                    const startDateTime = new Date(dateInput.value + 'T' + startInput.value);
                    if (!Number.isNaN(startDateTime.getTime()) && startDateTime <= new Date()) {
                        startInput.setCustomValidity('Choose a future date and start time.');
                        startInput.reportValidity();
                        return false;
                    }
                }
                return true;
            }

            function openModal() {
                document.getElementById('confirmName').textContent = document.getElementById('event_name').value.trim();
                document.getElementById('confirmLocation').textContent = document.getElementById('location').value.trim();
                document.getElementById('confirmDate').textContent = dateInput.value;
                document.getElementById('confirmTime').textContent = startInput.value + ' - ' + endInput.value;
                modal.hidden = false;
                document.body.style.overflow = 'hidden';
                cancel.focus();
            }

            function closeModal() {
                modal.hidden = true;
                document.body.style.overflow = '';
                reviewButton.focus();
            }

            form.addEventListener('submit', event => {
                if (submitting) {
                    event.preventDefault();
                    return;
                }
                if (!approved) {
                    event.preventDefault();
                    if (!form.reportValidity() || !validateSchedule()) return;
                    openModal();
                    return;
                }

                // The browser now performs the normal Laravel POST with CSRF.
                submitting = true;
                reviewButton.disabled = true;
                confirm.disabled = true;
                confirm.textContent = 'Creating Event...';
            });

            cancel.addEventListener('click', () => {
                approved = false;
                closeModal();
            });

            confirm.addEventListener('click', () => {
                if (submitting) return;
                approved = true;
                closeModal();
                form.requestSubmit();
            });

            modal.addEventListener('click', event => {
                if (event.target === modal) {
                    approved = false;
                    closeModal();
                }
            });

            document.addEventListener('keydown', event => {
                if (modal.hidden) return;
                if (event.key === 'Escape') {
                    approved = false;
                    closeModal();
                }
                // Keep keyboard focus inside this lightweight dialog.
                if (event.key === 'Tab') {
                    const buttons = [cancel, confirm];
                    const index = buttons.indexOf(document.activeElement);
                    if (event.shiftKey && index <= 0) {
                        event.preventDefault(); confirm.focus();
                    } else if (!event.shiftKey && index === buttons.length - 1) {
                        event.preventDefault(); cancel.focus();
                    }
                }
            });
        });
    </script>
</x-admin-layout>
