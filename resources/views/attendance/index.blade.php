<x-admin-layout>

<div class="space-y-8">

    <!-- ========================================================= -->
    <!-- HEADER -->
    <!-- ========================================================= -->

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <div class="flex items-center gap-3">

                <a
                    href="{{ route('my-events.show', $event->event_id) }}"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>


                <div>

                    <h1 class="text-3xl font-bold text-[#101064]">
                        Attendance Scanning
                    </h1>

                    <p class="mt-1 text-gray-500">
                        {{ $event->event_name }}
                    </p>

                </div>

            </div>

        </div>


        <div class="flex items-center gap-3">

            <!-- LIVE SYNC -->

            <div
                id="sync_status"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-600"
            >

                <span
                    id="sync_dot"
                    class="h-2.5 w-2.5 rounded-full bg-green-500"
                ></span>

                <span id="sync_text">
                    Live Sync
                </span>

            </div>


            <!-- TOTAL -->

            <div class="inline-flex items-center gap-3 rounded-xl bg-[#101064] px-5 py-2.5 text-white">

                <span class="text-sm">
                    Total Scanned
                </span>

                <span
                    id="attendance_count"
                    class="text-xl font-bold"
                >
                    {{ $attendanceCount }}
                </span>

            </div>

        </div>

    </div>





    <!-- ========================================================= -->
    <!-- MAIN GRID -->
    <!-- ========================================================= -->

    <div class="grid grid-cols-1 gap-8 xl:grid-cols-3">


        <!-- ===================================================== -->
        <!-- LEFT SIDE -->
        <!-- ===================================================== -->

        <div class="space-y-6 xl:col-span-2">


            <!-- EVENT DETAILS -->

            <div class="rounded-3xl border border-gray-100 bg-white p-7 shadow-sm">

                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                    <div>

                        <h2 class="text-xl font-semibold text-[#101064]">
                            Event Details
                        </h2>

                        <p class="mt-1 text-sm text-gray-500">
                            Attendance is being recorded for this event.
                        </p>

                    </div>


                    @if($event->status === 'Ongoing')

                        <span class="inline-flex w-fit rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                            Ongoing
                        </span>

                    @else

                        <span class="inline-flex w-fit rounded-full bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-700">
                            Upcoming
                        </span>

                    @endif

                </div>



                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">


                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Date
                        </p>

                        <p class="mt-1 font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
                        </p>

                    </div>



                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Schedule
                        </p>

                        <p class="mt-1 font-semibold text-gray-700">

                            {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}

                            -

                            {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}

                        </p>

                    </div>



                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Location
                        </p>

                        <p class="mt-1 font-semibold text-gray-700">
                            {{ $event->location }}
                        </p>

                    </div>



                    <div>

                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                            Department
                        </p>

                        <p class="mt-1 font-semibold text-gray-700">
                            {{ $event->department->department_name ?? 'University-wide Event' }}
                        </p>

                    </div>

                </div>

            </div>





            <!-- ================================================= -->
            <!-- RFID SCANNER -->
            <!-- ================================================= -->

            <div class="rounded-3xl border border-gray-100 bg-white p-8 shadow-sm">

                <div class="text-center">


                    <!-- SCANNER STATUS ICON -->

                    <div
                        id="scanner_icon"
                        class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-600"
                    >

                        <svg
                            class="h-8 w-8"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>

                    </div>


                    <h2
                        id="scanner_title"
                        class="mt-4 text-xl font-bold text-[#101064]"
                    >
                        Ready to Scan
                    </h2>


                    <p
                        id="scanner_description"
                        class="mt-2 text-sm text-gray-500"
                    >
                        Tap the student's RFID card on the reader.
                    </p>


                    <!-- RFID INPUT -->

                    <input
                        id="rfid_input"
                        type="text"
                        inputmode="numeric"
                        maxlength="10"
                        autofocus
                        autocomplete="off"
                        spellcheck="false"
                        class="mt-8 w-full rounded-2xl border-gray-300 text-center text-3xl font-semibold tracking-[0.25em] text-[#101064] focus:border-[#101064] focus:ring-[#101064]"
                        placeholder="Waiting for RFID..."
                    >


                    <p class="mt-3 text-xs text-gray-400">
                        RFID must contain exactly 10 digits.
                    </p>


                    <!-- RESULT MESSAGE -->

                    <div
                        id="result"
                        class="mt-6 min-h-[28px] text-base font-semibold"
                    ></div>

                </div>

            </div>


        </div>





        <!-- ===================================================== -->
        <!-- RIGHT SIDE -->
        <!-- ===================================================== -->

        <div class="space-y-6">


            <!-- ================================================= -->
            <!-- LATEST SCAN -->
            <!-- ================================================= -->

            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <h2 class="text-lg font-semibold text-[#101064]">
                        Latest Scan
                    </h2>

                    <span class="text-xs text-gray-400">
                        Most recent
                    </span>

                </div>


                <div
                    id="latest_scan"
                    class="mt-5"
                >

                    @if($latestAttendance && $latestAttendance->student)

                        <div class="rounded-2xl border border-green-100 bg-green-50 p-6 text-center">

                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white font-bold text-[#101064] shadow-sm">

                                {{ strtoupper(substr($latestAttendance->student->first_name, 0, 1)) }}

                            </div>


                            <h3 class="mt-4 text-xl font-bold text-[#101064]">

                                {{ $latestAttendance->student->first_name }}

                                {{ $latestAttendance->student->last_name }}

                            </h3>


                            <p class="mt-1 text-sm text-gray-600">
                                {{ $latestAttendance->student->student_number }}
                            </p>


                            <p class="mt-1 text-sm text-gray-500">

                                {{ $latestAttendance->student->program_code }}

                                @if($latestAttendance->student->program_name)

                                    • {{ $latestAttendance->student->program_name }}

                                @endif

                            </p>


                            <div class="mt-4 flex items-center justify-center gap-2">

                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    {{ $latestAttendance->status }}
                                </span>


                                <span class="text-xs text-gray-500">
                                    {{ $latestAttendance->time_in->format('h:i A') }}
                                </span>

                            </div>

                        </div>

                    @else

                        <div class="rounded-2xl bg-gray-50 px-5 py-10 text-center">

                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-gray-400">

                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>

                            </div>

                            <p class="mt-3 text-sm font-medium text-gray-500">
                                Waiting for first scan
                            </p>

                        </div>

                    @endif

                </div>

            </div>





            <!-- ================================================= -->
            <!-- LIVE ATTENDANCE -->
            <!-- ================================================= -->

            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <h2 class="text-lg font-semibold text-[#101064]">
                            Live Attendance
                        </h2>

                        <p class="mt-1 text-xs text-gray-400">
                            Latest 20 scans
                        </p>

                    </div>


                    <button
                        type="button"
                        id="refresh_feed"
                        class="text-sm font-semibold text-[#101064] hover:text-[#D4A017]"
                    >
                        Refresh
                    </button>

                </div>



                <div
                    id="attendance_feed"
                    class="mt-5 max-h-[520px] space-y-3 overflow-y-auto pr-1"
                >

                    @forelse($attendanceRecords as $record)

                        <div class="rounded-xl border border-gray-100 p-4">

                            <div class="flex items-start justify-between gap-3">

                                <div class="min-w-0">

                                    <p class="truncate font-semibold text-gray-800">

                                        {{ $record->student->first_name }}

                                        {{ $record->student->last_name }}

                                    </p>


                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $record->student->student_number }}
                                    </p>


                                    @if($record->student->program_code)

                                        <p class="mt-1 text-xs text-gray-400">
                                            {{ $record->student->program_code }}
                                        </p>

                                    @endif

                                </div>


                                <div class="shrink-0 text-right">

                                    <p class="text-sm font-medium text-gray-700">
                                        {{ $record->time_in->format('h:i A') }}
                                    </p>


                                    <p class="mt-1 text-xs text-green-600">
                                        {{ $record->status }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div
                            id="empty_feed"
                            class="rounded-xl bg-gray-50 px-4 py-8 text-center text-sm text-gray-400"
                        >
                            No attendance records yet.
                        </div>

                    @endforelse

                </div>

            </div>


        </div>

    </div>

</div>





<!-- ============================================================= -->
<!-- SCANNER SCRIPT -->
<!-- ============================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('rfid_input');
    const result = document.getElementById('result');

    const latest = document.getElementById('latest_scan');
    const feed = document.getElementById('attendance_feed');

    const counter = document.getElementById('attendance_count');

    const scannerTitle = document.getElementById('scanner_title');
    const scannerDescription = document.getElementById('scanner_description');
    const scannerIcon = document.getElementById('scanner_icon');

    const syncDot = document.getElementById('sync_dot');
    const syncText = document.getElementById('sync_text');

    const refreshButton = document.getElementById('refresh_feed');


    const scanUrl =
        @json(route('attendance.scan', $event->event_id));

    const feedUrl =
        @json(route('attendance.feed', $event->event_id));

    const csrfToken =
        @json(csrf_token());


    let processing = false;

    let scannerAvailable = true;



    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (value === null || value === undefined) {
            return '';
        }

        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

    }



    /*
    |--------------------------------------------------------------------------
    | Scanner State
    |--------------------------------------------------------------------------
    */

    function setReadyState() {

        if (!scannerAvailable) {
            return;
        }

        scannerTitle.textContent = 'Ready to Scan';

        scannerDescription.textContent =
            "Tap the student's RFID card on the reader.";

        scannerIcon.className =
            'mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-50 text-green-600';

        input.disabled = false;

    }



    function setProcessingState() {

        scannerTitle.textContent = 'Processing RFID...';

        scannerDescription.textContent =
            'Please wait while attendance is being recorded.';

        scannerIcon.className =
            'mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-50 text-yellow-600';

    }



    function setClosedState(status) {

        scannerAvailable = false;

        processing = false;

        input.disabled = true;

        input.value = '';

        scannerTitle.textContent = 'Attendance Closed';

        scannerDescription.textContent =
            `This event is ${status}. Attendance scanning is unavailable.`;

        scannerIcon.className =
            'mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 text-gray-400';

        result.innerHTML =
            '<span class="text-gray-500">Scanning has been disabled for this event.</span>';

    }



    /*
    |--------------------------------------------------------------------------
    | Result Message
    |--------------------------------------------------------------------------
    */

    function showSuccess(message) {

        result.innerHTML =
            `<span class="text-green-600">${escapeHtml(message)}</span>`;

    }



    function showError(message) {

        result.innerHTML =
            `<span class="text-red-600">${escapeHtml(message)}</span>`;

    }



    /*
    |--------------------------------------------------------------------------
    | Latest Scan Card
    |--------------------------------------------------------------------------
    */

    function renderLatest(record) {

        if (!record) {

            latest.innerHTML = `
                <div class="rounded-2xl bg-gray-50 px-5 py-10 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-white text-gray-400">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>

                    </div>

                    <p class="mt-3 text-sm font-medium text-gray-500">
                        Waiting for first scan
                    </p>

                </div>
            `;

            return;
        }


        const firstName = record.first_name ?? '';
        const lastName = record.last_name ?? '';

        const initial =
            firstName.length > 0
                ? firstName.charAt(0).toUpperCase()
                : 'S';


        let programText = '';

        if (record.program_code) {

            programText += escapeHtml(record.program_code);

        }

        if (record.program_name) {

            if (programText !== '') {
                programText += ' • ';
            }

            programText += escapeHtml(record.program_name);

        }


        latest.innerHTML = `
            <div class="rounded-2xl border border-green-100 bg-green-50 p-6 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white font-bold text-[#101064] shadow-sm">
                    ${escapeHtml(initial)}
                </div>

                <h3 class="mt-4 text-xl font-bold text-[#101064]">
                    ${escapeHtml(firstName)}
                    ${escapeHtml(lastName)}
                </h3>

                <p class="mt-1 text-sm text-gray-600">
                    ${escapeHtml(record.student_number)}
                </p>

                ${
                    programText !== ''
                        ? `
                            <p class="mt-1 text-sm text-gray-500">
                                ${programText}
                            </p>
                        `
                        : ''
                }

                <div class="mt-4 flex items-center justify-center gap-2">

                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                        ${escapeHtml(record.status)}
                    </span>

                    <span class="text-xs text-gray-500">
                        ${escapeHtml(record.time_display)}
                    </span>

                </div>

            </div>
        `;

    }



    /*
    |--------------------------------------------------------------------------
    | Live Feed
    |--------------------------------------------------------------------------
    */

    function renderFeed(records) {

        if (!Array.isArray(records) || records.length === 0) {

            feed.innerHTML = `
                <div class="rounded-xl bg-gray-50 px-4 py-8 text-center text-sm text-gray-400">
                    No attendance records yet.
                </div>
            `;

            return;
        }


        feed.innerHTML = records.map(record => {

            const firstName =
                escapeHtml(record.first_name ?? '');

            const lastName =
                escapeHtml(record.last_name ?? '');

            const studentNumber =
                escapeHtml(record.student_number ?? '');

            const programCode =
                escapeHtml(record.program_code ?? '');

            const time =
                escapeHtml(record.time_display ?? '');

            const status =
                escapeHtml(record.status ?? 'Present');


            return `
                <div class="rounded-xl border border-gray-100 p-4">

                    <div class="flex items-start justify-between gap-3">

                        <div class="min-w-0">

                            <p class="truncate font-semibold text-gray-800">
                                ${firstName} ${lastName}
                            </p>

                            <p class="mt-1 text-sm text-gray-500">
                                ${studentNumber}
                            </p>

                            ${
                                programCode
                                    ? `
                                        <p class="mt-1 text-xs text-gray-400">
                                            ${programCode}
                                        </p>
                                    `
                                    : ''
                            }

                        </div>

                        <div class="shrink-0 text-right">

                            <p class="text-sm font-medium text-gray-700">
                                ${time}
                            </p>

                            <p class="mt-1 text-xs text-green-600">
                                ${status}
                            </p>

                        </div>

                    </div>

                </div>
            `;

        }).join('');

    }



    /*
    |--------------------------------------------------------------------------
    | Refresh Live Attendance
    |--------------------------------------------------------------------------
    */

    async function refreshFeed() {

        if (processing) {
            return;
        }


        try {

            const response = await fetch(
                feedUrl,
                {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json'
                    },

                    cache: 'no-store'
                }
            );


            if (!response.ok) {

                throw new Error(
                    'Unable to refresh attendance.'
                );

            }


            const data = await response.json();


            syncDot.className =
                'h-2.5 w-2.5 rounded-full bg-green-500';

            syncText.textContent =
                'Live Sync';


            counter.textContent =
                data.total_count ?? 0;


            renderFeed(
                data.records ?? []
            );


            if (
                Array.isArray(data.records) &&
                data.records.length > 0
            ) {

                renderLatest(
                    data.records[0]
                );

            } else {

                renderLatest(null);

            }


            if (!data.scannable) {

                setClosedState(
                    data.event_status ?? 'closed'
                );

            }

        } catch (error) {

            syncDot.className =
                'h-2.5 w-2.5 rounded-full bg-red-500';

            syncText.textContent =
                'Sync Offline';

        }

    }



    /*
    |--------------------------------------------------------------------------
    | Process RFID
    |--------------------------------------------------------------------------
    */

    async function processRFID(rfid) {

        if (
            processing ||
            !scannerAvailable
        ) {
            return;
        }


        if (!/^\d{10}$/.test(rfid)) {

            showError(
                'RFID must contain exactly 10 digits.'
            );

            input.value = '';
            input.focus();

            return;
        }


        processing = true;

        setProcessingState();


        try {

            const response = await fetch(
                scanUrl,
                {
                    method: 'POST',

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken

                    },

                    body: JSON.stringify({
                        rfid_identifier: rfid
                    })
                }
            );


            let data;


            try {

                data = await response.json();

            } catch (error) {

                throw new Error(
                    'The server returned an invalid response.'
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Successful Scan
            |--------------------------------------------------------------------------
            */

            if (
                response.ok &&
                data.success
            ) {

                showSuccess(
                    data.message ?? 'Attendance recorded.'
                );


                counter.textContent =
                    data.total_count ?? counter.textContent;


                if (data.record) {

                    renderLatest(
                        data.record
                    );

                }


                await refreshFeed();

            }


            /*
            |--------------------------------------------------------------------------
            | Failed Scan
            |--------------------------------------------------------------------------
            */

            else {

                showError(
                    data.message ??
                    'Attendance could not be recorded.'
                );


                /*
                 * If duplicate, show the student's
                 * existing attendance information.
                 */

                if (
                    data.code === 'already_recorded' &&
                    data.record
                ) {

                    renderLatest(
                        data.record
                    );

                }


                if (
                    data.code === 'event_closed'
                ) {

                    setClosedState(
                        'closed'
                    );

                }

            }

        } catch (error) {

            showError(
                'Unable to connect to the server. Please try again.'
            );


            syncDot.className =
                'h-2.5 w-2.5 rounded-full bg-red-500';

            syncText.textContent =
                'Sync Offline';

        } finally {

            processing = false;

            input.value = '';


            if (scannerAvailable) {

                setReadyState();

                setTimeout(function () {
                    input.focus();
                }, 50);

            }

        }

    }



    /*
    |--------------------------------------------------------------------------
    | RFID Input Listener
    |--------------------------------------------------------------------------
    */

    input.addEventListener(
        'input',
        function () {

            /*
             * Keep numbers only.
             */

            this.value =
                this.value.replace(/\D/g, '');


            const rfid =
                this.value.trim();


            /*
             * Physical reader sends 10 digits.
             * Process only when exactly 10 are present.
             */

            if (rfid.length === 10) {

                processRFID(rfid);

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Enter Key Fallback
    |--------------------------------------------------------------------------
    |
    | Some keyboard-emulation readers may send Enter after the RFID.
    |
    */

    input.addEventListener(
        'keydown',
        function (event) {

            if (event.key !== 'Enter') {
                return;
            }


            event.preventDefault();


            const rfid =
                this.value.trim();


            if (rfid.length === 10) {

                processRFID(rfid);

            }

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Manual Refresh
    |--------------------------------------------------------------------------
    */

    refreshButton.addEventListener(
        'click',
        function () {

            refreshFeed();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Automatic Multi-Device Refresh
    |--------------------------------------------------------------------------
    |
    | Every 4 seconds, the page checks for new attendance records.
    | This allows scans from another computer to appear automatically.
    |
    */

    setInterval(function () {

        if (
            document.visibilityState === 'visible' &&
            !processing
        ) {

            refreshFeed();

        }

    }, 4000);



    /*
    |--------------------------------------------------------------------------
    | Initial Focus
    |--------------------------------------------------------------------------
    */

    setTimeout(function () {

        input.focus();

    }, 100);

});
</script>

</x-admin-layout>