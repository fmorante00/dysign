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


/* Department Staff Excuse Request Review — local UI styling, no Vite rebuild needed. */
.exr-filter-card, .exr-list-card {background:#fff;border:1px solid #dde4ef;border-radius:17px;box-shadow:0 3px 12px rgba(17,27,66,.035)}
.exr-filter-card {padding:22px 22px 18px}
.exr-overline {font-size:10px;letter-spacing:.20em;font-weight:800;color:#b77e04;text-transform:uppercase}
.exr-heading {color:#101064;font-size:20px;line-height:1.25;font-weight:800;margin-top:6px}
.exr-subtle {font-size:12px;color:#64748b;line-height:1.55}
.exr-quick {display:flex;flex-wrap:wrap;gap:8px}
.exr-quick button {background:#fff;border:1px solid #d9e1ef;border-radius:10px;padding:9px 15px;font-size:12px;color:#28365e;font-weight:700;cursor:pointer}
.exr-quick button[aria-pressed="true"] {background:#101064;color:#fff;border-color:#101064}
.exr-grid {display:grid;grid-template-columns:minmax(200px,1.5fr) minmax(150px,1fr) minmax(140px,1fr) minmax(145px,1fr);gap:14px;margin-top:20px}
.exr-field {min-width:0;display:flex;flex-direction:column;gap:6px}
.exr-field label {font-size:11px;color:#415477;font-weight:750}
.exr-field input,.exr-field select {width:100%;box-sizing:border-box;height:41px;border:1px solid #cdd8ea;background:#fff;color:#111b4d;border-radius:10px;padding:0 11px;font-size:12px;outline:none;min-width:0}
.exr-field input:focus,.exr-field select:focus {border-color:#d4a017;box-shadow:0 0 0 3px rgba(212,160,23,.15)}
.exr-toolbar {border-top:1px solid #edf0f6;margin-top:17px;padding-top:15px;display:flex;align-items:center;gap:11px;justify-content:space-between;flex-wrap:wrap}
.exr-btn {font-size:12px;font-weight:750;border-radius:10px;min-height:38px;padding:9px 15px;border:1px solid #d5deeb;background:#fff;color:#101064;cursor:pointer}
.exr-btn.primary {background:#101064;color:#fff;border-color:#101064}
.exr-btn.gold {background:#e5ae19;color:#101064;border-color:#d49e13}
.exr-btn:disabled {opacity:.42;cursor:not-allowed}
.exr-item-head {display:flex;justify-content:space-between;align-items:flex-start;gap:18px;padding:19px 21px}
.exr-person {font-size:17px;font-weight:850;color:#101064;margin-top:6px}
.exr-meta {font-size:12px;color:#64748b;line-height:1.6;margin-top:4px}
.exr-badges {display:flex;flex-wrap:wrap;gap:7px;align-items:center}
.exr-pill {display:inline-flex;align-items:center;border-radius:30px;padding:5px 10px;font-size:11px;font-weight:800}
.exr-pill.pending {color:#935900;background:#fff4d8}
.exr-pill.approved {color:#08734b;background:#e6faee}
.exr-pill.rejected {color:#b32136;background:#ffedf0}
.exr-pill.neutral {color:#273b69;background:#edf1fa}
.exr-item-summary {display:grid;grid-template-columns:1.2fr 1fr 1fr;gap:12px;padding:13px 21px;background:#f7f9fc;border-top:1px solid #edf0f7}
.exr-info-label {display:block;color:#7082a3;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.06em}
.exr-info-value {display:block;font-size:12px;line-height:1.45;color:#101a4e;font-weight:700;margin-top:4px;overflow-wrap:anywhere}
.exr-details {border-top:1px solid #e7edf6}
.exr-details summary {list-style:none;cursor:pointer;padding:13px 21px;display:flex;justify-content:space-between;color:#101064;font-size:12px;font-weight:800}
.exr-details summary::-webkit-details-marker {display:none}
.exr-details summary:after {content:'+';font-size:18px;color:#a97500;line-height:1}
.exr-details[open] summary:after {content:'−'}
.exr-detail-body {padding:4px 21px 21px;color:#475569;font-size:12px;line-height:1.6;display:grid;gap:13px}
.exr-section-title {font-size:10px;font-weight:850;color:#71809e;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px}
.exr-review-note {padding:12px 13px;border-radius:10px;background:#f4f7fd;border:1px solid #e4eaf5;white-space:pre-wrap;overflow-wrap:anywhere}
.exr-file {display:inline-flex;max-width:100%;align-items:center;gap:7px;background:#f8fafc;color:#101064;text-decoration:none;border:1px solid #dbe3ef;border-radius:9px;padding:8px 11px;font-size:12px;font-weight:650;overflow-wrap:anywhere}
.exr-file:hover {border-color:#d4a017;background:#fffbeb}
.exr-footer {border-top:1px solid #e5eaf3;padding:14px 18px;display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap}
.exr-pages {display:flex;gap:6px;align-items:center;flex-wrap:wrap}
.exr-pages button {border:1px solid #dae2f0;border-radius:8px;min-width:34px;height:34px;padding:0 9px;background:#fff;color:#101064;font-size:12px;font-weight:750;cursor:pointer}
.exr-pages button[aria-current="page"] {background:#101064;color:#fff}
.exr-pages button:disabled {opacity:.4;cursor:default}
@media (max-width:1100px) {.exr-grid {grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width:640px) {.exr-filter-card {padding:16px}.exr-grid {grid-template-columns:minmax(0,1fr);gap:11px}.exr-item-head {padding:16px;flex-direction:column}.exr-item-summary {grid-template-columns:1fr;gap:10px;padding:13px 16px}.exr-details summary,.exr-detail-body {padding-left:16px;padding-right:16px}.exr-toolbar {align-items:stretch}.exr-quick button {padding:8px 11px}}

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
    <section class="exr-filter-card" aria-label="Excuse request filters">
        <div class="flex flex-col gap-4 lg:flex-row lg:justify-between lg:items-end">
            <div>
                <p class="exr-overline">Review & Decision History</p>
                <h2 class="exr-heading">Excuse Request Queue</h2>
                <p class="exr-subtle mt-1">Search your department's requests, then expand a card to review its documents and decision.</p>
            </div>
            <div class="exr-quick" role="group" aria-label="Filter by decision status">
                <button type="button" class="filter-button" data-filter="All" aria-pressed="true">All</button>
                <button type="button" class="filter-button" data-filter="Pending" aria-pressed="false">Pending</button>
                <button type="button" class="filter-button" data-filter="Approved" aria-pressed="false">Approved</button>
                <button type="button" class="filter-button" data-filter="Rejected" aria-pressed="false">Rejected</button>
            </div>
        </div>
        <form id="excuseFilterForm" autocomplete="off">
            <div class="exr-grid">
                <div class="exr-field">
                    <label for="excuseSearch">Search student or event</label>
                    <input id="excuseSearch" type="search" placeholder="Name, student number, event, program…" />
                </div>
                <div class="exr-field">
                    <label for="excuseEventFilter">Event</label>
                    <select id="excuseEventFilter"><option value="">All department events</option></select>
                </div>
                <div class="exr-field">
                    <label for="excuseTimingFilter">Request timing</label>
                    <select id="excuseTimingFilter"><option value="">All timings</option><option value="Pre-Event">Pre-Event</option><option value="Post-Event">Post-Event</option></select>
                </div>
                <div class="exr-field">
                    <label for="excuseSort">Sort requests</label>
                    <select id="excuseSort"><option value="priority">Pending first (newest)</option><option value="newest">Newest first</option><option value="oldest">Oldest first</option><option value="student">Student A–Z</option><option value="event">Event A–Z</option></select>
                </div>
                <div class="exr-field">
                    <label for="excuseDateFrom">Submitted from</label>
                    <input id="excuseDateFrom" type="date" />
                </div>
                <div class="exr-field">
                    <label for="excuseDateTo">Submitted to</label>
                    <input id="excuseDateTo" type="date" />
                </div>
                <div class="exr-field">
                    <label for="excusePageSize">Requests per page</label>
                    <select id="excusePageSize"><option value="5">5 requests</option><option value="10">10 requests</option><option value="20">20 requests</option></select>
                </div>
                <div class="exr-field" style="justify-content:flex-end">
                    <button id="excuseApplyButton" class="exr-btn primary" type="submit">Apply Filters</button>
                </div>
            </div>
            <div class="exr-toolbar">
                <p id="excuseMatchCount" class="exr-subtle" role="status" aria-live="polite">Loading department requests…</p>
                <button class="exr-btn" id="excuseReset" type="button">Reset filters</button>
            </div>
        </form>
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

    <nav id="excusePagination" class="exr-list-card exr-footer hidden" aria-label="Excuse request pages">
        <p id="excusePageInfo" class="exr-subtle"></p>
        <div id="excusePageButtons" class="exr-pages"></div>
    </nav>

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

        let currentPage = 1;
        const filterFields = {
            search: document.getElementById('excuseSearch'),
            event: document.getElementById('excuseEventFilter'),
            timing: document.getElementById('excuseTimingFilter'),
            sort: document.getElementById('excuseSort'),
            from: document.getElementById('excuseDateFrom'),
            to: document.getElementById('excuseDateTo'),
            perPage: document.getElementById('excusePageSize')
        };
        const pageNav = document.getElementById('excusePagination');
        const pageInfo = document.getElementById('excusePageInfo');
        const pageButtons = document.getElementById('excusePageButtons');
        const matchCount = document.getElementById('excuseMatchCount');


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

            // Keep successful review feedback visible after refreshing the queue.


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
                populateEventFilter();
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
        | SEARCH, FILTERS, PAGINATION
        |--------------------------------------------------------------------------
        */
        function requestStudentName(request) {
            const student = request.student ?? {};
            return [student.first_name, student.middle_name, student.last_name]
                .filter(Boolean).join(' ') || 'Student';
        }

        function requestDate(request) {
            const d = new Date(request.submitted_at ?? 0);
            return Number.isFinite(d.getTime()) ? d : new Date(0);
        }

        function populateEventFilter() {
            const existing = filterFields.event.value;
            const unique = new Map();
            allRequests.forEach(request => {
                const event = request.event ?? {};
                const key = String(event.event_id ?? request.event_id ?? '');
                if (key && !unique.has(key)) unique.set(key, event.event_name ?? 'Untitled event');
            });
            filterFields.event.innerHTML = '<option value="">All department events</option>';
            [...unique.entries()].sort((a,b) => a[1].localeCompare(b[1])).forEach(([id,name]) => {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = name;
                filterFields.event.append(option);
            });
            if ([...unique.keys()].includes(existing)) filterFields.event.value = existing;
        }

        function setStatus(status) {
            currentFilter = status;
            currentPage = 1;
            document.querySelectorAll('.filter-button').forEach(button => {
                button.setAttribute('aria-pressed', String(button.dataset.filter === status));
            });
            renderRequests();
        }

        document.querySelectorAll('.filter-button').forEach(button => {
            button.addEventListener('click', () => setStatus(button.dataset.filter));
        });

        document.getElementById('excuseFilterForm').addEventListener('submit', event => {
            event.preventDefault();
            currentPage = 1;
            renderRequests();
        });
        document.getElementById('excuseReset').addEventListener('click', () => {
            document.getElementById('excuseFilterForm').reset();
            setStatus('All');
        });

        function filteredRequests() {
            const query = filterFields.search.value.trim().toLocaleLowerCase();
            const event = filterFields.event.value;
            const timing = filterFields.timing.value;
            const from = filterFields.from.value;
            const to = filterFields.to.value;
            const rows = allRequests.filter(request => {
                if (currentFilter !== 'All' && request.status !== currentFilter) return false;
                if (event && String(request.event?.event_id ?? request.event_id ?? '') !== event) return false;
                if (timing && request.request_timing !== timing) return false;
                const day = (request.submitted_at ?? '').slice(0, 10);
                if (from && day < from) return false;
                if (to && day > to) return false;
                if (query) {
                    const student = request.student ?? {};
                    const searchable = [requestStudentName(request), student.student_number,
                        student.program_code, student.program_name, request.event?.event_name]
                        .filter(Boolean).join(' ').toLocaleLowerCase();
                    if (!searchable.includes(query)) return false;
                }
                return true;
            });
            const sort = filterFields.sort.value;
            rows.sort((a,b) => {
                if (sort === 'student') return requestStudentName(a).localeCompare(requestStudentName(b));
                if (sort === 'event') return String(a.event?.event_name ?? '').localeCompare(String(b.event?.event_name ?? ''));
                if (sort === 'oldest') return requestDate(a) - requestDate(b);
                if (sort === 'priority') {
                    const aPending = a.status === 'Pending' ? 0 : 1;
                    const bPending = b.status === 'Pending' ? 0 : 1;
                    if (aPending !== bPending) return aPending - bPending;
                }
                return requestDate(b) - requestDate(a);
            });
            return rows;
        }

        function renderRequests() {
            const filtered = filteredRequests();
            const total = filtered.length;
            const perPage = Number(filterFields.perPage.value) || 5;
            const totalPages = Math.max(1, Math.ceil(total/perPage));
            currentPage = Math.max(1, Math.min(currentPage,totalPages));
            const from = (currentPage - 1)*perPage;
            matchCount.textContent = `${total} matching of ${allRequests.length} department requests`;
            requestList.innerHTML = '';
            if (!total) {
                requestList.classList.add('hidden');
                emptyState.classList.remove('hidden');
                pageNav.classList.add('hidden');
                return;
            }
            emptyState.classList.add('hidden');
            requestList.innerHTML = filtered.slice(from,from+perPage).map(renderRequest).join('');
            requestList.classList.remove('hidden');
            pageNav.classList.remove('hidden');
            pageInfo.textContent = `Showing ${from + 1}–${Math.min(from+perPage,total)} of ${total} requests`;
            pageButtons.innerHTML = '';
            function addButton(label, target, disabled, active=false) {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.disabled = disabled;
                if(active) button.setAttribute('aria-current','page');
                button.addEventListener('click', () => {
                    currentPage = target;
                    renderRequests();
                    document.getElementById('excuseFilterForm').scrollIntoView({behavior:'smooth',block:'start'});
                });
                pageButtons.append(button);
            }
            addButton('Previous', currentPage-1, currentPage === 1);
            for(let p=1;p<=totalPages;p++) {
                if (totalPages <= 7 || Math.abs(p-currentPage) <= 1 || p===1 || p===totalPages) {
                    addButton(String(p),p,false,p===currentPage);
                } else if (p===2 || p===totalPages-1) {
                    const span=document.createElement('span');span.textContent='…';span.style.padding='0 3px';
                    pageButtons.append(span);
                }
            }
            addButton('Next', currentPage+1, currentPage===totalPages);
        }

        function renderRequest(request) {
            const student = request.student ?? {};
            const status = ['Pending','Approved','Rejected'].includes(request.status) ? request.status : 'Pending';
            const timing = request.request_timing ?? '—';
            const submitted = request.submitted_at ? requestDate(request).toLocaleString() : '—';
            const documents = Array.isArray(request.documents) ? request.documents : [];
            const documentHtml = documents.length
                ? documents.map(document => {
                    const rid = Number(request.excuse_request_id);
                    const did = Number(document.excuse_request_document_id);
                    if (!Number.isSafeInteger(rid) || !Number.isSafeInteger(did)) return '';
                    const url = `/api/staff/excuse-requests/${rid}/documents/${did}`;
                    return `<a class="exr-file" href="${url}" target="_blank" rel="noopener noreferrer">📎 ${escapeHtml(document.file_name ?? 'Document')}</a>`;
                }).join('')
                : '<span class="exr-subtle">No supporting documents attached.</span>';
            const isPending = status==='Pending';
            const safeId = Number(request.excuse_request_id);
            const canReview = isPending && Number.isSafeInteger(safeId) && safeId > 0;
            return `
                <article class="exr-list-card">
                    <div class="exr-item-head">
                        <div class="min-w-0">
                            <div class="exr-badges">
                                <span class="exr-pill ${status.toLowerCase()}">${escapeHtml(status)}</span>
                                <span class="exr-pill neutral">${escapeHtml(timing)}</span>
                            </div>
                            <h3 class="exr-person">${escapeHtml(requestStudentName(request))}</h3>
                            <div class="exr-meta">${escapeHtml(student.student_number ?? 'No student number')} · ${escapeHtml(submitted)}</div>
                        </div>
                        ${canReview ? `<button type="button" class="exr-btn gold" onclick="openReviewModal(${safeId})">Review Request</button>` : ''}
                    </div>
                    <div class="exr-item-summary">
                        <div><span class="exr-info-label">Event</span><span class="exr-info-value">${escapeHtml(request.event?.event_name ?? '—')}</span></div>
                        <div><span class="exr-info-label">Program</span><span class="exr-info-value">${escapeHtml(student.program_code ?? student.program_name ?? '—')}</span></div>
                        <div><span class="exr-info-label">Documents</span><span class="exr-info-value">${documents.length} attached</span></div>
                    </div>
                    <details class="exr-details">
                        <summary>View reason, supporting documents & review details</summary>
                        <div class="exr-detail-body">
                            <div><div class="exr-section-title">Student's reason</div><div style="white-space:pre-wrap;overflow-wrap:anywhere">${escapeHtml(request.reason ?? 'No reason provided.')}</div></div>
                            <div><div class="exr-section-title">Supporting documents</div><div style="display:flex;gap:8px;flex-wrap:wrap">${documentHtml}</div></div>
                            <div><div class="exr-section-title">Review decision</div><div class="exr-review-note">${isPending ? 'Awaiting Department Staff decision.' : escapeHtml(request.review_remarks || 'No review remarks provided.')}</div></div>
                        </div>
                    </details>
                </article>`;
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