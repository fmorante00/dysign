<x-admin-layout>

<style>

    .excuse-hero {
        background:
            linear-gradient(
                100deg,
                rgba(16, 16, 100, .97) 0%,
                rgba(16, 16, 100, .92) 52%,
                rgba(16, 16, 100, .74) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }

</style>



<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            excuse-hero
            relative
            overflow-hidden
            rounded-[28px]
            px-7
            py-8
            text-white
            sm:px-8
            lg:px-10
            lg:py-10
        "
    >

        <div
            class="
                absolute
                bottom-0
                left-0
                h-1
                w-full
                bg-[#D4A017]
            "
        ></div>


        <div
            class="
                relative
                z-10
                flex
                flex-col
                gap-6
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >

            <div class="max-w-3xl">

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.32em]
                        text-[#E7C75B]
                    "
                >
                    Student Request
                </p>


                <h1
                    class="
                        mt-3
                        text-3xl
                        font-bold
                        tracking-tight
                        md:text-4xl
                    "
                >
                    My Excuse Requests
                </h1>


                <p
                    class="
                        mt-3
                        max-w-2xl
                        text-sm
                        leading-6
                        text-white/70
                    "
                >
                    Submit an excuse for an event you cannot attend
                    or an event you were unable to attend, together
                    with the required supporting document.
                </p>

            </div>


            <div
                class="
                    hidden
                    rounded-2xl
                    border
                    border-white/15
                    bg-white/10
                    px-5
                    py-4
                    backdrop-blur-sm
                    lg:block
                "
            >

                <p
                    class="
                        text-[10px]
                        font-semibold
                        uppercase
                        tracking-[0.20em]
                        text-[#E7C75B]
                    "
                >
                    Request Status
                </p>


                <p
                    class="
                        mt-1
                        text-sm
                        font-semibold
                    "
                >
                    Subject to Department Review
                </p>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- ALERTS --}}
    {{-- ====================================================== --}}

    <div
        id="successAlert"
        class="
            hidden
            rounded-xl
            border
            border-green-200
            bg-green-50
            px-5
            py-4
            text-sm
            font-medium
            text-green-700
        "
    ></div>


    <div
        id="errorAlert"
        class="
            hidden
            rounded-xl
            border
            border-red-200
            bg-red-50
            px-5
            py-4
            text-sm
            text-red-700
        "
    ></div>



    {{-- ====================================================== --}}
    {{-- REQUEST FORM --}}
    {{-- ====================================================== --}}

    <section>

        <div class="mb-4">

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.28em]
                    text-[#D4A017]
                "
            >
                Excuse Submission
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Submit an Excuse Request
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Select the event, explain your reason,
                and attach supporting documentation.
            </p>

        </div>



        <form
            id="excuseRequestForm"
            enctype="multipart/form-data"
        >

            <div
                class="
                    overflow-hidden
                    rounded-2xl
                    border
                    border-gray-200
                    bg-white
                    shadow-sm
                "
            >

                <div
                    class="
                        grid
                        grid-cols-1
                        lg:grid-cols-[1fr_300px]
                    "
                >


                    {{-- MAIN FORM --}}

                    <div
                        class="
                            border-b
                            border-gray-100
                            px-7
                            py-7
                            lg:border-b-0
                            lg:border-r
                        "
                    >


                        {{-- EVENT --}}

                        <div>

                            <label
                                for="event_id"
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Event
                            </label>


                            <select
                                name="event_id"
                                id="event_id"
                                required
                                class="
                                    w-full
                                    rounded-xl
                                    border
                                    border-gray-300
                                    bg-white
                                    px-4
                                    py-3
                                    text-sm
                                    text-gray-700
                                    outline-none
                                    transition
                                    focus:border-[#D4A017]
                                    focus:ring-2
                                    focus:ring-[#D4A017]/20
                                "
                            >

                                <option value="">
                                    Select an event
                                </option>


                                @foreach($events as $event)

                                    <option
                                        value="{{ $event->event_id }}"
                                    >
                                        {{ $event->event_name }}

                                        —

                                        {{
                                            \Carbon\Carbon::parse(
                                                $event->event_date
                                            )->format('M d, Y')
                                        }}

                                        @if($event->status)

                                            ({{ $event->status }})

                                        @endif

                                    </option>

                                @endforeach

                            </select>


                            @if($events->isEmpty())

                                <p
                                    class="
                                        mt-2
                                        text-xs
                                        text-orange-600
                                    "
                                >
                                    No applicable events are currently
                                    available for excuse submission.
                                </p>

                            @endif

                        </div>



                        {{-- REASON --}}

                        <div class="mt-6">

                            <label
                                for="reason"
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Reason for Absence
                            </label>


                            <textarea
                                name="reason"
                                id="reason"
                                rows="6"
                                required
                                minlength="10"
                                maxlength="3000"
                                placeholder="Explain why you cannot attend or why you were unable to attend the event..."
                                class="
                                    w-full
                                    resize-none
                                    rounded-xl
                                    border
                                    border-gray-300
                                    bg-white
                                    px-4
                                    py-3
                                    text-sm
                                    leading-6
                                    text-gray-700
                                    outline-none
                                    transition
                                    placeholder:text-gray-400
                                    focus:border-[#D4A017]
                                    focus:ring-2
                                    focus:ring-[#D4A017]/20
                                "
                            ></textarea>


                            <div
                                class="
                                    mt-2
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                    text-xs
                                    text-gray-400
                                "
                            >

                                <span>
                                    Minimum 10 characters.
                                </span>

                                <span id="reasonCounter">
                                    0 / 3000
                                </span>

                            </div>

                        </div>



                        {{-- SUPPORTING DOCUMENTS --}}

                        <div class="mt-6">

                            <label
                                for="documents"
                                class="
                                    mb-2
                                    block
                                    text-sm
                                    font-semibold
                                    text-gray-600
                                "
                            >
                                Supporting Document
                            </label>


                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-dashed
                                    border-gray-300
                                    bg-gray-50/60
                                    p-5
                                "
                            >

                                <input
                                    type="file"
                                    name="documents[]"
                                    id="documents"
                                    required
                                    multiple
                                    accept=".jpg,.jpeg,.png,.pdf"
                                    class="
                                        block
                                        w-full
                                        text-sm
                                        text-gray-600
                                        file:mr-4
                                        file:rounded-lg
                                        file:border-0
                                        file:bg-[#101064]
                                        file:px-4
                                        file:py-2.5
                                        file:text-sm
                                        file:font-semibold
                                        file:text-white
                                        hover:file:bg-[#D4A017]
                                        hover:file:text-[#101064]
                                    "
                                >


                                <p
                                    class="
                                        mt-3
                                        text-xs
                                        leading-5
                                        text-gray-400
                                    "
                                >
                                    Upload an excuse letter, medical
                                    document, or other supporting proof.
                                    JPG, JPEG, PNG, or PDF only.
                                    Maximum 10 MB per file and up to 5 files.
                                </p>


                                <div
                                    id="selectedDocuments"
                                    class="
                                        mt-4
                                        hidden
                                        space-y-2
                                    "
                                ></div>

                            </div>

                        </div>



                        {{-- ACTION --}}

                        <div
                            class="
                                mt-7
                                flex
                                justify-end
                            "
                        >

                            <button
                                type="submit"
                                id="submitButton"
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    gap-2
                                    rounded-xl
                                    bg-[#101064]
                                    px-6
                                    py-3
                                    text-sm
                                    font-semibold
                                    text-white
                                    transition
                                    hover:bg-[#D4A017]
                                    hover:text-[#101064]
                                    disabled:cursor-not-allowed
                                    disabled:opacity-60
                                "
                            >

                                <svg
                                    class="h-4 w-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M12 4v16m8-8H4"
                                    />
                                </svg>


                                <span id="submitButtonText">
                                    Submit Excuse Request
                                </span>

                            </button>

                        </div>

                    </div>



                    {{-- ====================================================== --}}
                    {{-- SIDE GUIDE --}}
                    {{-- ====================================================== --}}

                    <div
                        class="
                            bg-gray-50/60
                            px-7
                            py-7
                        "
                    >

                        <p
                            class="
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-[0.22em]
                                text-[#D4A017]
                            "
                        >
                            Submission Guide
                        </p>


                        <h3
                            class="
                                mt-2
                                text-lg
                                font-bold
                                text-[#101064]
                            "
                        >
                            Before Submitting
                        </h3>


                        <div class="mt-6 space-y-5">

                            <div class="flex gap-3">

                                <div
                                    class="
                                        flex
                                        h-8
                                        w-8
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-lg
                                        bg-white
                                        text-sm
                                        font-bold
                                        text-[#101064]
                                        shadow-sm
                                    "
                                >
                                    1
                                </div>


                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Select the event
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-gray-400">
                                        Only applicable events linked to
                                        your student account are shown.
                                    </p>

                                </div>

                            </div>


                            <div class="flex gap-3">

                                <div
                                    class="
                                        flex
                                        h-8
                                        w-8
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-lg
                                        bg-white
                                        text-sm
                                        font-bold
                                        text-[#101064]
                                        shadow-sm
                                    "
                                >
                                    2
                                </div>


                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Explain your reason
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-gray-400">
                                        Clearly state why you cannot attend
                                        or why you missed the event.
                                    </p>

                                </div>

                            </div>


                            <div class="flex gap-3">

                                <div
                                    class="
                                        flex
                                        h-8
                                        w-8
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-lg
                                        bg-white
                                        text-sm
                                        font-bold
                                        text-[#101064]
                                        shadow-sm
                                    "
                                >
                                    3
                                </div>


                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Attach proof
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-gray-400">
                                        Supporting documents are stored
                                        securely and can only be viewed
                                        by authorized users.
                                    </p>

                                </div>

                            </div>


                            <div class="flex gap-3">

                                <div
                                    class="
                                        flex
                                        h-8
                                        w-8
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-lg
                                        bg-white
                                        text-sm
                                        font-bold
                                        text-[#101064]
                                        shadow-sm
                                    "
                                >
                                    4
                                </div>


                                <div>

                                    <p class="text-sm font-semibold text-gray-700">
                                        Wait for review
                                    </p>

                                    <p class="mt-1 text-xs leading-5 text-gray-400">
                                        Submitted requests start as Pending
                                        and will be reviewed by Department Staff.
                                    </p>

                                </div>

                            </div>

                        </div>



                        <div
                            class="
                                mt-7
                                rounded-xl
                                border
                                border-[#D4A017]/20
                                bg-[#FFF8E1]
                                p-4
                            "
                        >

                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    text-[#7A5900]
                                "
                            >
                                Automatic Classification
                            </p>


                            <p
                                class="
                                    mt-1
                                    text-xs
                                    leading-5
                                    text-[#8A6A16]
                                "
                            >
                                DySign automatically identifies whether
                                your submission is a Pre-Event or
                                Post-Event excuse.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </form>

    </section>



    {{-- ====================================================== --}}
    {{-- REQUEST HISTORY --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                mb-4
                flex
                items-end
                justify-between
                gap-4
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.28em]
                        text-[#D4A017]
                    "
                >
                    Request Records
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Submission History
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Monitor the review status of your submitted requests.
                </p>

            </div>


            <span
                id="requestCount"
                class="
                    rounded-full
                    bg-[#F1F2FA]
                    px-3
                    py-1.5
                    text-xs
                    font-semibold
                    text-[#101064]
                "
            >
                0 Requests
            </span>

        </div>



        {{-- LOADING --}}

        <div
            id="loadingState"
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                px-6
                py-12
                text-center
            "
        >
            <p class="text-sm text-gray-500">
                Loading excuse requests...
            </p>
        </div>



        {{-- EMPTY --}}

        <div
            id="emptyState"
            class="
                hidden
                rounded-2xl
                border
                border-gray-200
                bg-white
                px-6
                py-12
                text-center
            "
        >

            <p
                class="
                    font-semibold
                    text-[#101064]
                "
            >
                No excuse requests yet
            </p>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Your submitted requests will appear here.
            </p>

        </div>



        {{-- REQUEST LIST --}}

        <div
            id="requestList"
            class="
                hidden
                space-y-4
            "
        ></div>

    </section>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const form =
            document.getElementById(
                'excuseRequestForm'
            );


        const documents =
            document.getElementById(
                'documents'
            );


        const selectedDocuments =
            document.getElementById(
                'selectedDocuments'
            );


        const reason =
            document.getElementById(
                'reason'
            );


        const reasonCounter =
            document.getElementById(
                'reasonCounter'
            );


        const submitButton =
            document.getElementById(
                'submitButton'
            );


        const submitButtonText =
            document.getElementById(
                'submitButtonText'
            );


        const successAlert =
            document.getElementById(
                'successAlert'
            );


        const errorAlert =
            document.getElementById(
                'errorAlert'
            );


        const loadingState =
            document.getElementById(
                'loadingState'
            );


        const emptyState =
            document.getElementById(
                'emptyState'
            );


        const requestList =
            document.getElementById(
                'requestList'
            );


        const requestCount =
            document.getElementById(
                'requestCount'
            );


        const csrfToken =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                ?.getAttribute(
                    'content'
                );



        /*
        |--------------------------------------------------------------------------
        | REASON COUNTER
        |--------------------------------------------------------------------------
        */

        reason.addEventListener(
            'input',
            function () {

                reasonCounter.textContent =
                    `${reason.value.length} / 3000`;

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SELECTED DOCUMENTS
        |--------------------------------------------------------------------------
        */

        documents.addEventListener(
            'change',
            function () {

                selectedDocuments.innerHTML =
                    '';


                const files =
                    Array.from(
                        documents.files
                    );


                if (
                    files.length === 0
                ) {

                    selectedDocuments
                        .classList
                        .add(
                            'hidden'
                        );

                    return;
                }


                selectedDocuments
                    .classList
                    .remove(
                        'hidden'
                    );


                files.forEach(
                    function (file) {

                        const size =
                            (
                                file.size
                                / 1024
                                / 1024
                            )
                            .toFixed(2);


                        const item =
                            document.createElement(
                                'div'
                            );


                        item.className =
                            'flex items-center justify-between gap-4 rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-600';


                        item.innerHTML =
                            `
                                <span class="truncate">
                                    ${escapeHtml(file.name)}
                                </span>

                                <span class="shrink-0 text-gray-400">
                                    ${size} MB
                                </span>
                            `;


                        selectedDocuments
                            .appendChild(
                                item
                            );

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SUBMIT REQUEST
        |--------------------------------------------------------------------------
        */

        form.addEventListener(
            'submit',
            async function (event) {

                event.preventDefault();


                hideAlerts();


                if (
                    !form.checkValidity()
                ) {

                    form.reportValidity();

                    return;
                }


                submitButton.disabled =
                    true;


                submitButtonText.textContent =
                    'Submitting...';


                try {

                    const formData =
                        new FormData(
                            form
                        );


                    const response =
                        await fetch(
                            '/api/student/excuse-requests',
                            {

                                method:
                                    'POST',

                                credentials:
                                    'same-origin',

                                headers: {

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                },

                                body:
                                    formData,

                            }
                        );


                    const data =
                        await response.json();


                    if (!response.ok) {

                        throw new Error(
                            getErrorMessage(
                                data
                            )
                        );

                    }


                    successAlert.textContent =
                        data.message;


                    successAlert
                        .classList
                        .remove(
                            'hidden'
                        );


                    form.reset();


                    reasonCounter.textContent =
                        '0 / 3000';


                    selectedDocuments.innerHTML =
                        '';


                    selectedDocuments
                        .classList
                        .add(
                            'hidden'
                        );


                    await loadRequests();

                }
                catch (error) {

                    errorAlert.textContent =
                        error.message;


                    errorAlert
                        .classList
                        .remove(
                            'hidden'
                        );

                }
                finally {

                    submitButton.disabled =
                        false;


                    submitButtonText.textContent =
                        'Submit Excuse Request';

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | LOAD REQUEST HISTORY
        |--------------------------------------------------------------------------
        */

        async function loadRequests() {

            loadingState
                .classList
                .remove(
                    'hidden'
                );


            requestList
                .classList
                .add(
                    'hidden'
                );


            emptyState
                .classList
                .add(
                    'hidden'
                );


            try {

                const response =
                    await fetch(
                        '/api/student/excuse-requests',
                        {

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-Requested-With':
                                    'XMLHttpRequest',

                            },

                        }
                    );


                const data =
                    await response.json();


                loadingState
                    .classList
                    .add(
                        'hidden'
                    );


                if (!response.ok) {

                    throw new Error(
                        data.message
                        ?? 'Unable to load excuse requests.'
                    );

                }


                requestCount.textContent =
                    `${data.count} Request${data.count === 1 ? '' : 's'}`;


                if (
                    !data.data
                    || data.data.length === 0
                ) {

                    emptyState
                        .classList
                        .remove(
                            'hidden'
                        );

                    return;
                }


                requestList.innerHTML =
                    data.data
                        .map(
                            renderRequest
                        )
                        .join('');


                requestList
                    .classList
                    .remove(
                        'hidden'
                    );

            }
            catch (error) {

                loadingState
                    .classList
                    .add(
                        'hidden'
                    );


                errorAlert.textContent =
                    error.message;


                errorAlert
                    .classList
                    .remove(
                        'hidden'
                    );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | RENDER REQUEST
        |--------------------------------------------------------------------------
        */

        function renderRequest(
            request
        ) {

            const status =
                request.status
                ?? 'Pending';


            let statusClass =
                'bg-blue-50 text-blue-700';


            let statusDot =
                'bg-blue-500';


            if (
                status === 'Approved'
            ) {

                statusClass =
                    'bg-green-50 text-green-700';

                statusDot =
                    'bg-green-500';

            }


            if (
                status === 'Rejected'
            ) {

                statusClass =
                    'bg-red-50 text-red-700';

                statusDot =
                    'bg-red-500';

            }


            const submittedDate =
                request.submitted_at
                    ? new Date(
                        request.submitted_at
                    ).toLocaleString()
                    : '—';


            const documentHtml =
                Array.isArray(
                    request.documents
                )
                && request.documents.length > 0
                    ? `
                        <div class="mt-4">

                            <p
                                class="
                                    mb-2
                                    text-xs
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Supporting Documents
                            </p>

                            <div class="flex flex-wrap gap-2">

                                ${request.documents.map(
                                    document => {

                                        const url =
                                            `/api/student/excuse-requests/${request.excuse_request_id}/documents/${document.excuse_request_document_id}`;


                                        return `
                                            <a
                                                href="${url}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-lg
                                                    border
                                                    border-gray-200
                                                    bg-gray-50
                                                    px-3
                                                    py-2
                                                    text-xs
                                                    font-medium
                                                    text-[#101064]
                                                    transition
                                                    hover:border-[#D4A017]
                                                    hover:bg-[#FFF8E1]
                                                "
                                            >
                                                📎
                                                ${escapeHtml(document.file_name)}
                                            </a>
                                        `;

                                    }
                                ).join('')}

                            </div>

                        </div>
                    `
                    : '';


            const reviewHtml =
                request.review_remarks
                    ? `
                        <div
                            class="
                                mt-4
                                rounded-xl
                                bg-gray-50
                                px-4
                                py-3
                            "
                        >

                            <p
                                class="
                                    text-xs
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Review Remarks
                            </p>

                            <p
                                class="
                                    mt-1
                                    text-sm
                                    leading-6
                                    text-gray-600
                                "
                            >
                                ${escapeHtml(request.review_remarks)}
                            </p>

                        </div>
                    `
                    : '';


            return `
                <article
                    class="
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        p-6
                        shadow-sm
                    "
                >

                    <div
                        class="
                            flex
                            flex-col
                            gap-4
                            sm:flex-row
                            sm:items-start
                            sm:justify-between
                        "
                    >

                        <div>

                            <p
                                class="
                                    text-lg
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                ${escapeHtml(
                                    request.event?.event_name
                                    ?? 'Event'
                                )}
                            </p>


                            <div
                                class="
                                    mt-2
                                    flex
                                    flex-wrap
                                    gap-2
                                "
                            >

                                <span
                                    class="
                                        rounded-full
                                        bg-[#F1F2FA]
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        text-[#101064]
                                    "
                                >
                                    ${escapeHtml(
                                        request.request_timing
                                    )}
                                </span>


                                <span
                                    class="
                                        inline-flex
                                        items-center
                                        gap-2
                                        rounded-full
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        ${statusClass}
                                    "
                                >

                                    <span
                                        class="
                                            h-1.5
                                            w-1.5
                                            rounded-full
                                            ${statusDot}
                                        "
                                    ></span>

                                    ${escapeHtml(status)}

                                </span>

                            </div>

                        </div>


                        <p
                            class="
                                text-xs
                                text-gray-400
                            "
                        >
                            ${escapeHtml(submittedDate)}
                        </p>

                    </div>


                    <div class="mt-5">

                        <p
                            class="
                                text-xs
                                font-bold
                                uppercase
                                tracking-wider
                                text-gray-400
                            "
                        >
                            Reason
                        </p>


                        <p
                            class="
                                mt-2
                                whitespace-pre-line
                                text-sm
                                leading-6
                                text-gray-600
                            "
                        >
                            ${escapeHtml(request.reason)}
                        </p>

                    </div>


                    ${documentHtml}

                    ${reviewHtml}

                </article>
            `;

        }



        function getErrorMessage(
            data
        ) {

            if (
                data.errors
            ) {

                const errors =
                    Object.values(
                        data.errors
                    )
                    .flat();


                if (
                    errors.length > 0
                ) {

                    return errors[0];

                }

            }


            return (
                data.message
                ?? 'Unable to submit excuse request.'
            );

        }



        function hideAlerts() {

            successAlert
                .classList
                .add(
                    'hidden'
                );


            errorAlert
                .classList
                .add(
                    'hidden'
                );

        }



        function escapeHtml(
            value
        ) {

            return String(
                value ?? ''
            )
            .replaceAll(
                '&',
                '&amp;'
            )
            .replaceAll(
                '<',
                '&lt;'
            )
            .replaceAll(
                '>',
                '&gt;'
            )
            .replaceAll(
                '"',
                '&quot;'
            )
            .replaceAll(
                "'",
                '&#039;'
            );

        }



        /*
        |--------------------------------------------------------------------------
        | INITIAL LOAD
        |--------------------------------------------------------------------------
        */

        loadRequests();

    }
);

</script>

</x-admin-layout>