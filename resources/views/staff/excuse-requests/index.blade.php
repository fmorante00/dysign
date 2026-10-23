<x-admin-layout>

<style>

    .excuse-review-hero {
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
            excuse-review-hero
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
                    Excuse Validation
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
                    Excuse Request Review
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
                    Review student absence requests,
                    verify supporting documents,
                    and record the appropriate decision.
                </p>

            </div>


            <div
                class="
                    rounded-2xl
                    border
                    border-white/15
                    bg-white/10
                    px-5
                    py-4
                    backdrop-blur-sm
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
                    Pending Review
                </p>


                <p
                    id="heroPendingCount"
                    class="
                        mt-1
                        text-2xl
                        font-bold
                    "
                >
                    0
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
    {{-- SUMMARY --}}
    {{-- ====================================================== --}}

    <section
        class="
            grid
            grid-cols-1
            overflow-hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
            shadow-sm
            sm:grid-cols-2
            xl:grid-cols-4
        "
    >

        <div
            class="
                border-b
                border-gray-100
                px-6
                py-5
                sm:border-r
                xl:border-b-0
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
                Total Requests
            </p>

            <p
                id="totalCount"
                class="
                    mt-2
                    text-3xl
                    font-bold
                    text-[#101064]
                "
            >
                0
            </p>

        </div>


        <div
            class="
                border-b
                border-gray-100
                px-6
                py-5
                xl:border-b-0
                xl:border-r
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
                Pending
            </p>

            <p
                id="pendingCount"
                class="
                    mt-2
                    text-3xl
                    font-bold
                    text-blue-600
                "
            >
                0
            </p>

        </div>


        <div
            class="
                border-b
                border-gray-100
                px-6
                py-5
                sm:border-r
                sm:border-b-0
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
                Approved
            </p>

            <p
                id="approvedCount"
                class="
                    mt-2
                    text-3xl
                    font-bold
                    text-green-600
                "
            >
                0
            </p>

        </div>


        <div
            class="
                px-6
                py-5
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
                Rejected
            </p>

            <p
                id="rejectedCount"
                class="
                    mt-2
                    text-3xl
                    font-bold
                    text-red-600
                "
            >
                0
            </p>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- FILTERS --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                flex
                flex-col
                gap-4
                md:flex-row
                md:items-end
                md:justify-between
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
                    Student Requests
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Review Queue
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Pending requests are shown first.
                </p>

            </div>


            <div
                class="
                    flex
                    flex-wrap
                    gap-2
                "
            >

                <button
                    type="button"
                    data-filter="All"
                    class="
                        filter-button
                        rounded-lg
                        bg-[#101064]
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        text-white
                        transition
                    "
                >
                    All
                </button>


                <button
                    type="button"
                    data-filter="Pending"
                    class="
                        filter-button
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        text-gray-600
                        transition
                        hover:border-[#D4A017]
                    "
                >
                    Pending
                </button>


                <button
                    type="button"
                    data-filter="Approved"
                    class="
                        filter-button
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        text-gray-600
                        transition
                        hover:border-[#D4A017]
                    "
                >
                    Approved
                </button>


                <button
                    type="button"
                    data-filter="Rejected"
                    class="
                        filter-button
                        rounded-lg
                        border
                        border-gray-200
                        bg-white
                        px-4
                        py-2
                        text-xs
                        font-semibold
                        text-gray-600
                        transition
                        hover:border-[#D4A017]
                    "
                >
                    Rejected
                </button>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- LOADING --}}
    {{-- ====================================================== --}}

    <div
        id="loadingState"
        class="
            rounded-2xl
            border
            border-gray-200
            bg-white
            px-6
            py-14
            text-center
        "
    >

        <p class="text-sm text-gray-500">
            Loading excuse requests...
        </p>

    </div>



    {{-- ====================================================== --}}
    {{-- EMPTY --}}
    {{-- ====================================================== --}}

    <div
        id="emptyState"
        class="
            hidden
            rounded-2xl
            border
            border-gray-200
            bg-white
            px-6
            py-14
            text-center
        "
    >

        <p
            class="
                font-semibold
                text-[#101064]
            "
        >
            No excuse requests found
        </p>


        <p
            class="
                mt-1
                text-sm
                text-gray-400
            "
        >
            Student excuse requests will appear here.
        </p>

    </div>



    {{-- ====================================================== --}}
    {{-- REQUEST LIST --}}
    {{-- ====================================================== --}}

    <div
        id="requestList"
        class="
            hidden
            space-y-5
        "
    ></div>



    {{-- ====================================================== --}}
    {{-- REVIEW MODAL --}}
    {{-- ====================================================== --}}

    <div
        id="reviewModal"
        class="
            fixed
            inset-0
            z-[100]
            hidden
            items-center
            justify-center
            bg-black/50
            p-4
        "
    >

        <div
            class="
                w-full
                max-w-xl
                overflow-hidden
                rounded-2xl
                bg-white
                shadow-2xl
            "
        >

            <div
                class="
                    flex
                    items-start
                    justify-between
                    gap-4
                    border-b
                    border-gray-100
                    px-6
                    py-5
                "
            >

                <div>

                    <p
                        class="
                            text-xs
                            font-semibold
                            uppercase
                            tracking-[0.25em]
                            text-[#D4A017]
                        "
                    >
                        Review Decision
                    </p>


                    <h3
                        id="modalTitle"
                        class="
                            mt-1
                            text-xl
                            font-bold
                            text-[#101064]
                        "
                    >
                        Excuse Request
                    </h3>

                </div>


                <button
                    type="button"
                    id="closeModalButton"
                    class="
                        flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        rounded-lg
                        bg-gray-100
                        text-gray-500
                        transition
                        hover:bg-gray-200
                    "
                >
                    ×
                </button>

            </div>


            <form id="reviewForm">

                <div
                    class="
                        space-y-5
                        px-6
                        py-6
                    "
                >

                    <input
                        type="hidden"
                        id="reviewRequestId"
                    >


                    <div>

                        <label
                            for="decision"
                            class="
                                mb-2
                                block
                                text-sm
                                font-semibold
                                text-gray-600
                            "
                        >
                            Decision
                        </label>


                        <select
                            id="decision"
                            required
                            class="
                                w-full
                                rounded-xl
                                border
                                border-gray-300
                                px-4
                                py-3
                                text-sm
                                outline-none
                                transition
                                focus:border-[#D4A017]
                                focus:ring-2
                                focus:ring-[#D4A017]/20
                            "
                        >

                            <option value="">
                                Select decision
                            </option>

                            <option value="Approved">
                                Approve
                            </option>

                            <option value="Rejected">
                                Reject
                            </option>

                        </select>

                    </div>


                    <div>

                        <label
                            for="remarks"
                            class="
                                mb-2
                                block
                                text-sm
                                font-semibold
                                text-gray-600
                            "
                        >
                            Review Remarks
                        </label>


                        <textarea
                            id="remarks"
                            rows="5"
                            maxlength="2000"
                            placeholder="Enter remarks or explanation for the decision..."
                            class="
                                w-full
                                resize-none
                                rounded-xl
                                border
                                border-gray-300
                                px-4
                                py-3
                                text-sm
                                leading-6
                                outline-none
                                transition
                                focus:border-[#D4A017]
                                focus:ring-2
                                focus:ring-[#D4A017]/20
                            "
                        ></textarea>


                        <p
                            id="remarksHelp"
                            class="
                                mt-2
                                text-xs
                                text-gray-400
                            "
                        >
                            Remarks are optional for approved requests.
                        </p>

                    </div>

                </div>


                <div
                    class="
                        flex
                        justify-end
                        gap-3
                        border-t
                        border-gray-100
                        bg-gray-50
                        px-6
                        py-4
                    "
                >

                    <button
                        type="button"
                        id="cancelModalButton"
                        class="
                            rounded-lg
                            border
                            border-gray-300
                            bg-white
                            px-5
                            py-2.5
                            text-sm
                            font-semibold
                            text-gray-600
                            transition
                            hover:bg-gray-100
                        "
                    >
                        Cancel
                    </button>


                    <button
                        type="submit"
                        id="reviewSubmitButton"
                        class="
                            rounded-lg
                            bg-[#101064]
                            px-5
                            py-2.5
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
                        Save Decision
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        let allRequests = [];

        let currentFilter =
            'All';


        const requestList =
            document.getElementById(
                'requestList'
            );


        const loadingState =
            document.getElementById(
                'loadingState'
            );


        const emptyState =
            document.getElementById(
                'emptyState'
            );


        const successAlert =
            document.getElementById(
                'successAlert'
            );


        const errorAlert =
            document.getElementById(
                'errorAlert'
            );


        const totalCount =
            document.getElementById(
                'totalCount'
            );


        const pendingCount =
            document.getElementById(
                'pendingCount'
            );


        const approvedCount =
            document.getElementById(
                'approvedCount'
            );


        const rejectedCount =
            document.getElementById(
                'rejectedCount'
            );


        const heroPendingCount =
            document.getElementById(
                'heroPendingCount'
            );


        const reviewModal =
            document.getElementById(
                'reviewModal'
            );


        const reviewForm =
            document.getElementById(
                'reviewForm'
            );


        const reviewRequestId =
            document.getElementById(
                'reviewRequestId'
            );


        const decision =
            document.getElementById(
                'decision'
            );


        const remarks =
            document.getElementById(
                'remarks'
            );


        const remarksHelp =
            document.getElementById(
                'remarksHelp'
            );


        const modalTitle =
            document.getElementById(
                'modalTitle'
            );


        const reviewSubmitButton =
            document.getElementById(
                'reviewSubmitButton'
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
        | LOAD REQUESTS
        |--------------------------------------------------------------------------
        */

        async function loadRequests() {

            hideAlerts();


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
                        '/api/staff/excuse-requests',
                        {

                            credentials:
                                'same-origin',

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


                if (!response.ok) {

                    throw new Error(
                        data.message
                        ?? 'Unable to load excuse requests.'
                    );

                }


                allRequests =
                    data.data
                    ?? [];


                updateSummary();

                renderRequests();

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

                loadingState
                    .classList
                    .add(
                        'hidden'
                    );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        function updateSummary() {

            const pending =
                allRequests.filter(
                    request =>
                        request.status
                        === 'Pending'
                )
                .length;


            const approved =
                allRequests.filter(
                    request =>
                        request.status
                        === 'Approved'
                )
                .length;


            const rejected =
                allRequests.filter(
                    request =>
                        request.status
                        === 'Rejected'
                )
                .length;


            totalCount.textContent =
                allRequests.length;


            pendingCount.textContent =
                pending;


            approvedCount.textContent =
                approved;


            rejectedCount.textContent =
                rejected;


            heroPendingCount.textContent =
                pending;

        }



        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.filter-button'
            )
            .forEach(
                button => {

                    button.addEventListener(
                        'click',
                        function () {

                            currentFilter =
                                button.dataset.filter;


                            document
                                .querySelectorAll(
                                    '.filter-button'
                                )
                                .forEach(
                                    item => {

                                        item.classList.remove(
                                            'bg-[#101064]',
                                            'text-white'
                                        );


                                        item.classList.add(
                                            'border',
                                            'border-gray-200',
                                            'bg-white',
                                            'text-gray-600'
                                        );

                                    }
                                );


                            button.classList.remove(
                                'border',
                                'border-gray-200',
                                'bg-white',
                                'text-gray-600'
                            );


                            button.classList.add(
                                'bg-[#101064]',
                                'text-white'
                            );


                            renderRequests();

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | RENDER REQUESTS
        |--------------------------------------------------------------------------
        */

        function renderRequests() {

            const filtered =
                currentFilter === 'All'
                    ? allRequests
                    : allRequests.filter(
                        request =>
                            request.status
                            === currentFilter
                    );


            requestList.innerHTML =
                '';


            if (
                filtered.length === 0
            ) {

                requestList
                    .classList
                    .add(
                        'hidden'
                    );


                emptyState
                    .classList
                    .remove(
                        'hidden'
                    );


                return;
            }


            emptyState
                .classList
                .add(
                    'hidden'
                );


            requestList.innerHTML =
                filtered
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



        /*
        |--------------------------------------------------------------------------
        | RENDER SINGLE REQUEST
        |--------------------------------------------------------------------------
        */

        function renderRequest(
            request
        ) {

            const student =
                request.student
                ?? {};


            const studentName =
                [
                    student.first_name,
                    student.middle_name,
                    student.last_name
                ]
                .filter(Boolean)
                .join(' ');


            const status =
                request.status
                ?? 'Pending';


            let statusClass =
                'bg-blue-50 text-blue-700';


            if (
                status === 'Approved'
            ) {

                statusClass =
                    'bg-green-50 text-green-700';

            }


            if (
                status === 'Rejected'
            ) {

                statusClass =
                    'bg-red-50 text-red-700';

            }


            const submitted =
                request.submitted_at
                    ? new Date(
                        request.submitted_at
                    )
                    .toLocaleString()
                    : '—';


            const documents =
                Array.isArray(
                    request.documents
                )
                    ? request.documents
                    : [];


            const documentHtml =
                documents.length > 0
                    ? documents
                        .map(
                            document => {

                                const url =
                                    `/api/staff/excuse-requests/${request.excuse_request_id}/documents/${document.excuse_request_document_id}`;


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
                        )
                        .join('')
                    : `
                        <span
                            class="
                                text-xs
                                italic
                                text-gray-400
                            "
                        >
                            No supporting document
                        </span>
                    `;


            const reviewButton =
                status === 'Pending'
                    ? `
                        <button
                            type="button"
                            onclick="openReviewModal(${request.excuse_request_id})"
                            class="
                                rounded-lg
                                bg-[#101064]
                                px-4
                                py-2.5
                                text-xs
                                font-semibold
                                text-white
                                transition
                                hover:bg-[#D4A017]
                                hover:text-[#101064]
                            "
                        >
                            Review Request
                        </button>
                    `
                    : '';


            const reviewDetails =
                status !== 'Pending'
                    ? `
                        <div
                            class="
                                mt-5
                                rounded-xl
                                bg-gray-50
                                px-4
                                py-4
                            "
                        >

                            <p
                                class="
                                    text-xs
                                    font-bold
                                    uppercase
                                    tracking-wider
                                    text-gray-400
                                "
                            >
                                Review Result
                            </p>


                            <p
                                class="
                                    mt-2
                                    text-sm
                                    text-gray-600
                                "
                            >
                                ${
                                    request.review_remarks
                                        ? escapeHtml(
                                            request.review_remarks
                                        )
                                        : 'No review remarks provided.'
                                }
                            </p>

                        </div>
                    `
                    : '';


            return `
                <article
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
                            flex
                            flex-col
                            gap-5
                            px-6
                            py-6
                            lg:flex-row
                            lg:items-start
                            lg:justify-between
                        "
                    >

                        <div class="min-w-0 flex-1">

                            <div
                                class="
                                    flex
                                    flex-wrap
                                    items-center
                                    gap-2
                                "
                            >

                                <span
                                    class="
                                        rounded-full
                                        px-3
                                        py-1
                                        text-xs
                                        font-semibold
                                        ${statusClass}
                                    "
                                >
                                    ${escapeHtml(status)}
                                </span>


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

                            </div>


                            <h3
                                class="
                                    mt-4
                                    text-xl
                                    font-bold
                                    text-[#101064]
                                "
                            >
                                ${escapeHtml(
                                    studentName
                                    || 'Student'
                                )}
                            </h3>


                            <p
                                class="
                                    mt-1
                                    text-sm
                                    text-gray-500
                                "
                            >
                                ${escapeHtml(
                                    student.student_number
                                    ?? ''
                                )}
                            </p>


                            <div
                                class="
                                    mt-5
                                    grid
                                    grid-cols-1
                                    gap-4
                                    sm:grid-cols-2
                                    lg:grid-cols-3
                                "
                            >

                                <div>

                                    <p
                                        class="
                                            text-xs
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Event
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        ${escapeHtml(
                                            request.event?.event_name
                                            ?? '—'
                                        )}
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="
                                            text-xs
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Program
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        ${escapeHtml(
                                            student.program_code
                                            ?? student.program_name
                                            ?? '—'
                                        )}
                                    </p>

                                </div>


                                <div>

                                    <p
                                        class="
                                            text-xs
                                            font-semibold
                                            uppercase
                                            tracking-wider
                                            text-gray-400
                                        "
                                    >
                                        Submitted
                                    </p>

                                    <p
                                        class="
                                            mt-1
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        ${escapeHtml(submitted)}
                                    </p>

                                </div>

                            </div>


                            <div class="mt-6">

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


                            <div class="mt-5">

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


                                <div
                                    class="
                                        flex
                                        flex-wrap
                                        gap-2
                                    "
                                >
                                    ${documentHtml}
                                </div>

                            </div>


                            ${reviewDetails}

                        </div>


                        <div
                            class="
                                shrink-0
                            "
                        >
                            ${reviewButton}
                        </div>

                    </div>

                </article>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | OPEN REVIEW MODAL
        |--------------------------------------------------------------------------
        */

        window.openReviewModal =
            function (
                requestId
            ) {

                const request =
                    allRequests.find(
                        item =>
                            Number(
                                item.excuse_request_id
                            )
                            ===
                            Number(
                                requestId
                            )
                    );


                if (!request) {

                    return;

                }


                reviewRequestId.value =
                    request.excuse_request_id;


                decision.value =
                    '';


                remarks.value =
                    '';


                const student =
                    request.student
                    ?? {};


                modalTitle.textContent =
                    [
                        student.first_name,
                        student.last_name
                    ]
                    .filter(Boolean)
                    .join(' ')
                    || 'Excuse Request';


                reviewModal
                    .classList
                    .remove(
                        'hidden'
                    );


                reviewModal
                    .classList
                    .add(
                        'flex'
                    );

            };



        /*
        |--------------------------------------------------------------------------
        | CLOSE MODAL
        |--------------------------------------------------------------------------
        */

        function closeModal() {

            reviewModal
                .classList
                .add(
                    'hidden'
                );


            reviewModal
                .classList
                .remove(
                    'flex'
                );


            reviewForm.reset();

        }


        document
            .getElementById(
                'closeModalButton'
            )
            .addEventListener(
                'click',
                closeModal
            );


        document
            .getElementById(
                'cancelModalButton'
            )
            .addEventListener(
                'click',
                closeModal
            );



        /*
        |--------------------------------------------------------------------------
        | DECISION CHANGE
        |--------------------------------------------------------------------------
        */

        decision.addEventListener(
            'change',
            function () {

                if (
                    decision.value
                    === 'Rejected'
                ) {

                    remarks.required =
                        true;


                    remarksHelp.textContent =
                        'Remarks are required when rejecting a request.';

                }
                else {

                    remarks.required =
                        false;


                    remarksHelp.textContent =
                        'Remarks are optional for approved requests.';

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | SUBMIT REVIEW
        |--------------------------------------------------------------------------
        */

        reviewForm.addEventListener(
            'submit',
            async function (
                event
            ) {

                event.preventDefault();


                hideAlerts();


                if (
                    !reviewForm.checkValidity()
                ) {

                    reviewForm.reportValidity();

                    return;
                }


                const requestId =
                    reviewRequestId.value;


                reviewSubmitButton.disabled =
                    true;


                reviewSubmitButton.textContent =
                    'Saving...';


                try {

                    const response =
                        await fetch(
                            `/api/staff/excuse-requests/${requestId}/review`,
                            {

                                method:
                                    'PATCH',

                                credentials:
                                    'same-origin',

                                headers: {

                                    'Content-Type':
                                        'application/json',

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                    'X-CSRF-TOKEN':
                                        csrfToken,

                                },

                                body:
                                    JSON.stringify({

                                        decision:
                                            decision.value,

                                        remarks:
                                            remarks.value.trim()
                                            || null,

                                    }),

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


                    closeModal();


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

                    reviewSubmitButton.disabled =
                        false;


                    reviewSubmitButton.textContent =
                        'Save Decision';

                }

            }
        );



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
                ?? 'Unable to process the request.'
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