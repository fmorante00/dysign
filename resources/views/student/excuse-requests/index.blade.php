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


    /* Excuse Requests: scoped UI enhancements. No build or other page affected. */
    .exh-summary { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:14px; }
    .exh-stat { border:1px solid #e0e5ef; border-radius:17px; background:#fff; padding:19px 20px; box-shadow:0 2px 3px rgba(13,19,52,.035); }
    .exh-stat-top {display:flex;align-items:center;justify-content:space-between;gap:12px;}
    .exh-stat-label { color:#61708b;font-size:13px;font-weight:650; }
    .exh-stat-icon { width:39px;height:39px;border-radius:12px;display:grid;place-items:center;background:#f0f1ff;color:#101064;font-size:17px; }
    .exh-stat-value { margin-top:12px;font-size:30px;line-height:1.15;font-weight:800;color:#101064; }
    .exh-stat-note {margin-top:7px;color:#718096;font-size:11px;}
    .exh-filter-shell {border:1px solid #e1e5ee;border-radius:17px;background:#fff;padding:19px 20px;margin-bottom:17px;}
    .exh-filter-head {display:flex;justify-content:space-between;align-items:start;gap:10px;margin-bottom:15px;}
    .exh-filter-title{color:#101064;font-size:15px;font-weight:800;}
    .exh-filter-subtitle{font-size:12px;color:#73809a;margin-top:3px;}
    .exh-filter-grid{display:grid;grid-template-columns:minmax(190px,2fr) repeat(3,minmax(125px,1fr));gap:12px;align-items:end;}
    .exh-date-grid{display:grid;grid-template-columns:repeat(2,minmax(120px,1fr));gap:12px;}
    .exh-filter-field{display:block;min-width:0;}
    .exh-filter-field>span{display:block;font-size:11px;font-weight:750;color:#4e5a73;margin-bottom:7px;}
    .exh-input{display:block;width:100%;min-width:0;min-height:42px;background:#fff;border:1px solid #ccd5e5;border-radius:11px;padding:9px 11px;color:#101064;font-size:13px;}
    .exh-input:focus {outline:2px solid rgba(212,160,23,.32);border-color:#d4a017;}
    .exh-filter-bottom{display:flex;flex-wrap:wrap;align-items:end;gap:12px;margin-top:12px;justify-content:space-between;}
    .exh-filter-bottom .exh-date-grid{flex:1 1 310px;max-width:450px;}
    .exh-filter-actions{display:flex;gap:8px;align-items:end;}
    .exh-btn{min-height:42px;border:1px solid #ced6e4;background:#fff;border-radius:11px;color:#101064;font-size:12px;font-weight:700;padding:10px 16px;cursor:pointer;}
    .exh-btn:hover{background:#f5f6fc;}
    .exh-btn-navy{border-color:#101064;background:#101064;color:#fff;}
    .exh-btn-navy:hover{background:#24247e;}
    .exh-results-meta{color:#64738c;font-size:12px;margin:2px 0 12px;}
    .exh-entry{border:1px solid #e1e5ed;background:#fff;border-radius:17px;overflow:hidden;box-shadow:0 2px 3px rgba(13,19,52,.035);}
    .exh-entry-summary{list-style:none;cursor:pointer;display:flex;justify-content:space-between;align-items:center;gap:18px;padding:18px 21px;}
    .exh-entry-summary::-webkit-details-marker{display:none;}
    .exh-entry-summary:focus-visible{outline:3px solid #d4a017;outline-offset:-3px;}
    .exh-entry-main{min-width:0;flex:1;}
    .exh-entry-name{color:#101064;font-size:15px;font-weight:800;overflow-wrap:anywhere;}
    .exh-entry-sub{color:#78859a;font-size:11px;margin-top:5px;}
    .exh-entry-tags{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px;}
    .exh-pill{display:inline-flex;align-items:center;gap:5px;border-radius:999px;padding:5px 9px;font-size:10px;font-weight:750;}
    .exh-pill-type{background:#f1f2fa;color:#101064;}
    .exh-pill-pending{background:#fff3d8;color:#9b6500;}
    .exh-pill-approved{background:#e9f8f0;color:#087548;}
    .exh-pill-rejected{background:#fff0f0;color:#b52b37;}
    .exh-entry-aside{display:flex;flex-direction:column;align-items:end;gap:9px;text-align:right;flex-shrink:0;}
    .exh-entry-date{color:#8793a6;font-size:11px;}
    .exh-entry-toggle{color:#101064;font-size:11px;font-weight:750;}
    .exh-entry[open] .exh-entry-toggle-icon{transform:rotate(180deg);}
    .exh-entry-toggle-icon{display:inline-block;transition:transform .15s;}
    .exh-entry-body{border-top:1px solid #e8ecf3;padding:20px 21px;background:#fcfdff;}
    .exh-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;}
    .exh-detail-panel{background:#fff;border:1px solid #e6ebf2;border-radius:12px;padding:15px;min-width:0;}
    .exh-detail-heading{color:#101064;text-transform:uppercase;letter-spacing:.08em;font-size:10px;font-weight:800;}
    .exh-detail-text{margin-top:10px;color:#44526b;white-space:pre-wrap;overflow-wrap:anywhere;font-size:13px;line-height:1.6;}
    .exh-documents{margin-top:10px;display:flex;flex-wrap:wrap;gap:8px;}
    .exh-document{display:inline-flex;max-width:100%;align-items:center;gap:7px;color:#101064;background:#f7f8ff;border:1px solid #dfe4f4;border-radius:10px;padding:9px 11px;font-size:12px;font-weight:700;text-decoration:none;overflow-wrap:anywhere;}
    .exh-document:hover{border-color:#d4a017;background:#fff9e7;}
    .exh-pager{display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;margin-top:17px;padding:12px 4px;}
    .exh-page-summary{font-size:12px;color:#69788f;}
    .exh-page-buttons{display:flex;flex-wrap:wrap;align-items:center;gap:6px;}
    .exh-page-buttons button{min-width:35px;min-height:35px;padding:7px 11px;border:1px solid #dfe5ee;border-radius:9px;background:#fff;color:#101064;font-size:12px;font-weight:750;cursor:pointer;}
    .exh-page-buttons button[aria-current="page"]{background:#101064;color:#fff;border-color:#101064;}
    .exh-page-buttons button:disabled{opacity:.4;cursor:not-allowed;}
    .exh-page-buttons button:not(:disabled):hover{border-color:#d4a017;}
    .exh-empty-icon{display:grid;place-items:center;margin:0 auto 14px;width:44px;height:44px;border-radius:15px;background:#f2f3fc;color:#101064;font-size:20px;}
    @media(max-width:1100px){ .exh-filter-grid{grid-template-columns:repeat(2,minmax(0,1fr));}.exh-summary{grid-template-columns:repeat(2,minmax(0,1fr));} }
    @media(max-width:650px){ .exh-summary{gap:10px;}.exh-stat{padding:15px;}.exh-stat-value{font-size:25px;}.exh-filter-grid,.exh-detail-grid{grid-template-columns:1fr;}.exh-filter-bottom .exh-date-grid{max-width:none;}.exh-filter-shell{padding:14px;}.exh-entry-summary{padding:15px;align-items:start;flex-direction:column;}.exh-entry-aside{align-items:start;text-align:left;}.exh-entry-body{padding:14px;}.exh-pager{flex-direction:column;align-items:start;}}

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

    {{-- Request totals are calculated from the signed-in student's own API results. --}}
    <section class="exh-summary" aria-label="Excuse request overview">
        <div class="exh-stat"><div class="exh-stat-top"><span class="exh-stat-label">Total Requests</span><span class="exh-stat-icon" aria-hidden="true">▣</span></div><p id="exhStatTotal" class="exh-stat-value">–</p><p class="exh-stat-note">All submitted requests</p></div>
        <div class="exh-stat"><div class="exh-stat-top"><span class="exh-stat-label">Pending Review</span><span class="exh-stat-icon" style="background:#fff7e2;color:#a76b00" aria-hidden="true">◷</span></div><p id="exhStatPending" class="exh-stat-value" style="color:#b47500">–</p><p class="exh-stat-note">Awaiting staff decision</p></div>
        <div class="exh-stat"><div class="exh-stat-top"><span class="exh-stat-label">Approved</span><span class="exh-stat-icon" style="background:#e9f9f0;color:#067648" aria-hidden="true">✓</span></div><p id="exhStatApproved" class="exh-stat-value" style="color:#057348">–</p><p class="exh-stat-note">Accepted excuse requests</p></div>
        <div class="exh-stat"><div class="exh-stat-top"><span class="exh-stat-label">Rejected</span><span class="exh-stat-icon" style="background:#fff0f0;color:#b52535" aria-hidden="true">×</span></div><p id="exhStatRejected" class="exh-stat-value" style="color:#b52535">–</p><p class="exh-stat-note">Requests not approved</p></div>
    </section>

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




        {{-- Student-only history filters: no change to the server's review logic. --}}
        <div class="exh-filter-shell" id="exhFilters">
            <div class="exh-filter-head"><div><h3 class="exh-filter-title">Search &amp; Filter Requests</h3><p class="exh-filter-subtitle">Find a submitted excuse or review its status.</p></div></div>
            <div class="exh-filter-grid">
                <label class="exh-filter-field"><span>Search event</span><input id="exhSearch" type="search" class="exh-input" placeholder="Search event name or venue" autocomplete="off"></label>
                <label class="exh-filter-field"><span>Review status</span><select id="exhStatus" class="exh-input"><option value="">All statuses</option><option value="Pending">Pending</option><option value="Approved">Approved</option><option value="Rejected">Rejected</option></select></label>
                <label class="exh-filter-field"><span>Request timing</span><select id="exhTiming" class="exh-input"><option value="">All types</option><option value="Pre-Event">Pre-Event</option><option value="Post-Event">Post-Event</option></select></label>
                <label class="exh-filter-field"><span>Sort requests</span><select id="exhSort" class="exh-input"><option value="newest">Newest submitted</option><option value="oldest">Oldest submitted</option><option value="event-newest">Latest event date</option><option value="event-oldest">Earliest event date</option></select></label>
            </div>
            <div class="exh-filter-bottom">
                <div class="exh-date-grid">
                    <label class="exh-filter-field"><span>Event date from</span><input id="exhDateFrom" type="date" class="exh-input"></label>
                    <label class="exh-filter-field"><span>Event date to</span><input id="exhDateTo" type="date" class="exh-input"></label>
                </div>
                <div class="exh-filter-actions">
                    <label class="exh-filter-field"><span>Per page</span><select id="exhPerPage" class="exh-input"><option value="5">5</option><option value="10">10</option><option value="20">20</option></select></label>
                    <button id="exhReset" type="button" class="exh-btn">Clear Filters</button>
                </div>
            </div>
        </div>
        <p id="exhResultsMeta" class="exh-results-meta" role="status" aria-live="polite"></p>


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

        <nav id="exhPagination" class="exh-pager hidden" aria-label="Request history pages"></nav>

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


        const exhSearch = document.getElementById('exhSearch');
        const exhStatus = document.getElementById('exhStatus');
        const exhTiming = document.getElementById('exhTiming');
        const exhSort = document.getElementById('exhSort');
        const exhDateFrom = document.getElementById('exhDateFrom');
        const exhDateTo = document.getElementById('exhDateTo');
        const exhPerPage = document.getElementById('exhPerPage');
        const exhReset = document.getElementById('exhReset');
        const exhResultsMeta = document.getElementById('exhResultsMeta');
        const exhPagination = document.getElementById('exhPagination');
        const exhStatTotal = document.getElementById('exhStatTotal');
        const exhStatPending = document.getElementById('exhStatPending');
        const exhStatApproved = document.getElementById('exhStatApproved');
        const exhStatRejected = document.getElementById('exhStatRejected');
        let allExcuseRequests = [];
        let excusePage = 1;

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


                const selectedFiles = Array.from(documents.files || []);
                if (selectedFiles.length < 1 || selectedFiles.length > 5 ||
                    selectedFiles.some(file => file.size > 10 * 1024 * 1024)) {
                    errorAlert.textContent = 'Attach 1–5 JPG, PNG, or PDF files, up to 10 MB each.';
                    errorAlert.classList.remove('hidden');
                    return;
                }

                const isConfirmed = window.DySignUI?.confirm
                    ? await window.DySignUI.confirm({
                        title: 'Submit excuse request?',
                        message: 'Your excuse and supporting documents will be sent to Department Staff for review. Verify your event and details before continuing.',
                        confirmText: 'Submit Request',
                        variant: 'warning'
                    })
                    : window.confirm('Submit this excuse request for Department Staff review?');
                if (!isConfirmed) return;

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

        function dateKey(value) {
            // Laravel date fields may be plain YYYY-MM-DD or ISO datetimes.
            return value ? String(value).slice(0, 10) : '';
        }

        function dateLabel(value) {
            const key = dateKey(value);
            if (!/^\d{4}-\d{2}-\d{2}$/.test(key)) return 'Date not available';
            const [year, month, day] = key.split('-').map(Number);
            return new Intl.DateTimeFormat('en-PH', { month:'short',day:'numeric',year:'numeric' })
                .format(new Date(year, month - 1, day));
        }

        function submittedLabel(value) {
            if (!value) return 'Submission date unavailable';
            const valueDate = new Date(value);
            return Number.isNaN(valueDate.valueOf()) ? 'Submission date unavailable' :
                valueDate.toLocaleString('en-PH', { year:'numeric', month:'short', day:'numeric',hour:'numeric',minute:'2-digit' });
        }

        function updateRequestStatistics() {
            exhStatTotal.textContent = allExcuseRequests.length;
            exhStatPending.textContent = allExcuseRequests.filter(item => (item.status || 'Pending') === 'Pending').length;
            exhStatApproved.textContent = allExcuseRequests.filter(item => item.status === 'Approved').length;
            exhStatRejected.textContent = allExcuseRequests.filter(item => item.status === 'Rejected').length;
            requestCount.textContent = `${allExcuseRequests.length} Request${allExcuseRequests.length === 1 ? '' : 's'}`;
        }

        function renderFilteredHistory() {
            const search = exhSearch.value.toLocaleLowerCase().trim();
            const selectedStatus = exhStatus.value;
            const selectedTiming = exhTiming.value;
            const selectedFrom = exhDateFrom.value;
            const selectedTo = exhDateTo.value;
            const sort = exhSort.value;
            const perPage = Number(exhPerPage.value) || 5;

            const matches = allExcuseRequests.filter(item => {
                const eventName = String(item.event?.event_name || '').toLocaleLowerCase();
                const venue = String(item.event?.venue || item.event?.location || '').toLocaleLowerCase();
                const eventDate = dateKey(item.event?.event_date);
                return (!search || eventName.includes(search) || venue.includes(search)) &&
                    (!selectedStatus || (item.status || 'Pending') === selectedStatus) &&
                    (!selectedTiming || item.request_timing === selectedTiming) &&
                    (!selectedFrom || (eventDate && eventDate >= selectedFrom)) &&
                    (!selectedTo || (eventDate && eventDate <= selectedTo));
            });

            const sorted = matches.slice().sort((a, b) => {
                const eventA = dateKey(a.event?.event_date);
                const eventB = dateKey(b.event?.event_date);
                const submittedA = a.submitted_at ? new Date(a.submitted_at).getTime() || 0 : 0;
                const submittedB = b.submitted_at ? new Date(b.submitted_at).getTime() || 0 : 0;
                const bySubmit = submittedB - submittedA || (Number(b.excuse_request_id) - Number(a.excuse_request_id));
                if (sort === 'oldest') return -bySubmit;
                if (sort === 'event-newest') return eventB.localeCompare(eventA) || bySubmit;
                if (sort === 'event-oldest') return eventA.localeCompare(eventB) || bySubmit;
                return bySubmit;
            });

            const pageCount = Math.max(1, Math.ceil(sorted.length / perPage));
            excusePage = Math.min(Math.max(1, excusePage), pageCount);
            const start = (excusePage - 1) * perPage;
            const pageItems = sorted.slice(start, start + perPage);
            const from = sorted.length ? start + 1 : 0;
            const to = Math.min(start + perPage, sorted.length);
            exhResultsMeta.textContent = `Showing ${from}–${to} of ${sorted.length} matching request${sorted.length === 1 ? '' : 's'} (${allExcuseRequests.length} total)`;

            loadingState.classList.add('hidden');
            if (!allExcuseRequests.length) {
                emptyState.querySelector('p.font-semibold').textContent = 'No excuse requests yet';
                emptyState.querySelector('p.text-sm').textContent = 'Your submitted requests will appear here.';
            } else if (!sorted.length) {
                emptyState.querySelector('p.font-semibold').textContent = 'No matching excuse requests';
                emptyState.querySelector('p.text-sm').textContent = 'Try a different search or clear the filters.';
            }
            emptyState.classList.toggle('hidden', sorted.length > 0);
            requestList.classList.toggle('hidden', sorted.length === 0);
            requestList.innerHTML = pageItems.map(renderRequest).join('');

            if (pageCount <= 1 || !sorted.length) {
                exhPagination.classList.add('hidden');
                exhPagination.innerHTML = '';
                return;
            }
            const pageButtons = [];
            pageButtons.push(`<button type="button" data-exh-page="${excusePage - 1}" ${excusePage === 1 ? 'disabled' : ''} aria-label="Previous page">Previous</button>`);
            const visiblePages = new Set([1, pageCount, excusePage - 1, excusePage, excusePage + 1]);
            let last = 0;
            for (let p = 1; p <= pageCount; p++) {
                if (!visiblePages.has(p)) continue;
                if (last && p > last + 1) pageButtons.push('<span aria-hidden="true" style="padding:0 2px;color:#73809a">…</span>');
                pageButtons.push(`<button type="button" data-exh-page="${p}" ${p === excusePage ? 'aria-current="page"' : ''} aria-label="Page ${p}">${p}</button>`);
                last = p;
            }
            pageButtons.push(`<button type="button" data-exh-page="${excusePage + 1}" ${excusePage === pageCount ? 'disabled' : ''} aria-label="Next page">Next</button>`);
            exhPagination.innerHTML = `<p class="exh-page-summary">Page ${excusePage} of ${pageCount}</p><div class="exh-page-buttons">${pageButtons.join('')}</div>`;
            exhPagination.classList.remove('hidden');
        }

        [exhSearch,exhStatus,exhTiming,exhSort,exhDateFrom,exhDateTo,exhPerPage].forEach(control => {
            control.addEventListener(control === exhSearch ? 'input' : 'change', () => {
                excusePage = 1;
                renderFilteredHistory();
            });
        });
        exhReset.addEventListener('click', () => {
            exhSearch.value = '';
            exhStatus.value = '';
            exhTiming.value = '';
            exhSort.value = 'newest';
            exhDateFrom.value = '';
            exhDateTo.value = '';
            exhPerPage.value = '5';
            excusePage = 1;
            renderFilteredHistory();
        });
        exhPagination.addEventListener('click', event => {
            const button = event.target.closest('button[data-exh-page]');
            if (!button || button.disabled) return;
            excusePage = Number(button.dataset.exhPage) || 1;
            renderFilteredHistory();
            document.getElementById('exhResultsMeta')?.scrollIntoView({block:'nearest',behavior:'smooth'});
        });

        async function loadRequests() {
            loadingState.classList.remove('hidden');
            requestList.classList.add('hidden');
            emptyState.classList.add('hidden');
            exhPagination.classList.add('hidden');
            try {
                const response = await fetch('/api/student/excuse-requests', {
                    credentials: 'same-origin',
                    headers: {'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}
                });
                const data = await response.json();
                if (!response.ok || !data.success || !Array.isArray(data.data)) {
                    throw new Error(data.message || 'Unable to load excuse requests.');
                }
                allExcuseRequests = data.data;
                updateRequestStatistics();
                excusePage = 1;
                renderFilteredHistory();
            } catch (error) {
                loadingState.classList.add('hidden');
                errorAlert.textContent = error.message || 'Unable to load excuse requests.';
                errorAlert.classList.remove('hidden');
                exhResultsMeta.textContent = 'Could not load request history.';
            }
        }

        function renderRequest(request) {
            const status = request.status || 'Pending';
            const style = status === 'Approved' ? 'exh-pill-approved' : status === 'Rejected' ? 'exh-pill-rejected' : 'exh-pill-pending';
            const eventDate = dateLabel(request.event?.event_date);
            const eventName = escapeHtml(request.event?.event_name || 'Event unavailable');
            const documents = Array.isArray(request.documents) ? request.documents : [];
            const documentsHtml = documents.length ? documents.map(doc => {
                const id = encodeURIComponent(String(request.excuse_request_id));
                const documentId = encodeURIComponent(String(doc.excuse_request_document_id));
                const url = `/api/student/excuse-requests/${id}/documents/${documentId}`;
                return `<a href="${url}" class="exh-document" target="_blank" rel="noopener noreferrer" aria-label="View supporting document ${escapeHtml(doc.file_name)}">📎 ${escapeHtml(doc.file_name || 'Document')}</a>`;
            }).join('') : '<p class="exh-detail-text">No supporting documents listed.</p>';
            const reviewHtml = request.review_remarks
                ? `<p class="exh-detail-text">${escapeHtml(request.review_remarks)}</p>`
                : '<p class="exh-detail-text">No review remarks available yet.</p>';
            return `<details class="exh-entry">
                <summary class="exh-entry-summary">
                    <div class="exh-entry-main">
                        <h3 class="exh-entry-name">${eventName}</h3>
                        <p class="exh-entry-sub">Event ${escapeHtml(eventDate)} · Submitted ${escapeHtml(submittedLabel(request.submitted_at))}</p>
                        <div class="exh-entry-tags"><span class="exh-pill exh-pill-type">${escapeHtml(request.request_timing || 'Excuse Request')}</span><span class="exh-pill ${style}">${escapeHtml(status)}</span></div>
                    </div>
                    <div class="exh-entry-aside"><span class="exh-entry-date">${documents.length} supporting document${documents.length === 1 ? '' : 's'}</span><span class="exh-entry-toggle">View details <span class="exh-entry-toggle-icon" aria-hidden="true">⌄</span></span></div>
                </summary>
                <div class="exh-entry-body">
                    <div class="exh-detail-grid">
                        <div class="exh-detail-panel"><p class="exh-detail-heading">Reason for Absence</p><p class="exh-detail-text">${escapeHtml(request.reason || 'No reason provided.')}</p></div>
                        <div class="exh-detail-panel"><p class="exh-detail-heading">Department Staff Review</p>${reviewHtml}</div>
                        <div class="exh-detail-panel" style="grid-column:1/-1"><p class="exh-detail-heading">Supporting Documents</p><div class="exh-documents">${documentsHtml}</div></div>
                    </div>
                </div>
            </details>`;
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