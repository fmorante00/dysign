<x-admin-layout>

<style>
    /* Evaluation Forms: same campus-photo hero as the DySign Event Management page. */
    .dysign-eval-hero {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        background-image: linear-gradient(100deg, rgba(16,16,100,.96) 0%, rgba(16,16,100,.89) 52%, rgba(16,16,100,.70) 100%), url('{{ asset('images/school.jpg') }}');
        background-position: center;
        background-size: cover;
    }
    .dysign-eval-hero-rings { position:absolute; inset:0; z-index:0; overflow:hidden; pointer-events:none; }
    .dysign-eval-ring { position:absolute; display:block; border:2px solid rgba(255,255,255,.25); border-radius:50%; right:-55px; top:-145px; width:410px; height:410px; }
    .dysign-eval-ring:nth-child(2) { right:0; top:-90px; width:300px; height:300px; border-color:rgba(255,255,255,.22); }
    .dysign-eval-ring:nth-child(3) { right:55px; top:-35px; width:190px; height:190px; border-color:rgba(255,255,255,.19); }
    /* Questionnaires are opened on demand in a true screen-level dialog. */
    #evaluationPreviewModal.dysign-eval-overlay { position:fixed !important; inset:0 !important; z-index:99999 !important; align-items:center; justify-content:center; padding:16px; background:rgba(9,14,45,.76); backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px); }
    #evaluationPreviewModal.dysign-eval-overlay.hidden { display:none !important; }
    #evaluationPreviewModal.dysign-eval-overlay:not(.hidden) { display:flex !important; }
    .dysign-eval-dialog { width:min(920px,100%); height:min(790px,calc(100dvh - 32px)); min-height:0; display:flex; flex-direction:column; overflow:hidden; border:1px solid #e3e7f0; border-radius:22px; background:#fff; box-shadow:0 28px 95px rgba(0,0,30,.38); }
    .dysign-eval-dialog-header { flex:none; padding:23px 28px 19px; background:#101064; color:#fff; border-bottom:3px solid #D4A017; }
    .dysign-eval-dialog-top { display:flex; align-items:flex-start; justify-content:space-between; gap:22px; }
    .dysign-eval-dialog-title { margin:8px 0 0; font-size:clamp(19px,2.4vw,25px); font-weight:800; line-height:1.28; overflow-wrap:anywhere; }
    .dysign-eval-dialog-event { margin:7px 0 0; font-size:13px; color:#d8dcff; }
    .dysign-eval-close { flex:none; width:40px; height:40px; display:inline-flex; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.3); border-radius:12px; background:rgba(255,255,255,.12); color:#fff; font-size:24px; line-height:1; cursor:pointer; }
    .dysign-eval-close:hover,.dysign-eval-close:focus-visible { background:rgba(255,255,255,.25); outline:2px solid #D4A017; outline-offset:2px; }
    .dysign-eval-dialog-meta { display:flex; flex-wrap:wrap; align-items:center; gap:12px; margin-top:15px; }
    #autoPublishInfo { color:#dfe2ff; font-size:11px; line-height:1.6; max-width:650px; }
    .dysign-eval-dialog-body { flex:1 1 auto; min-height:0; overflow-y:auto; overscroll-behavior:contain; background:#f8f9fc; padding:24px 28px; }
    .dysign-eval-intro { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; margin-bottom:15px; }
    .dysign-eval-questions { counter-reset:dysign-eval-question; display:grid; gap:13px; }
    .dysign-eval-questions > div { position:relative; counter-increment:dysign-eval-question; padding:19px 20px 19px 62px !important; border:1px solid #e0e6f0 !important; border-radius:16px !important; background:white !important; box-shadow:0 2px 8px rgba(16,16,100,.025); }
    .dysign-eval-questions > div::before { content:counter(dysign-eval-question, decimal-leading-zero); position:absolute; left:19px; top:18px; display:flex; align-items:center; justify-content:center; width:31px; height:31px; border-radius:9px; background:#f0f1ff; color:#101064; font-size:11px; font-weight:800; }
    .dysign-eval-questions > div p { line-height:1.65; }
    .dysign-eval-questions input, .dysign-eval-questions select, .dysign-eval-questions textarea { pointer-events:none; }
    .dysign-eval-dialog-footer { flex:none; display:flex; justify-content:space-between; align-items:center; gap:15px; padding:14px 28px; border-top:1px solid #e6e9f1; background:white; }
    .dysign-eval-footer-close { padding:11px 18px; flex:none; border-radius:10px; background:#101064; color:#fff; font-size:12px; font-weight:700; cursor:pointer; }
    .dysign-eval-footer-close:hover { background:#25257e; }
    @media (max-width:640px) {
        .dysign-eval-ring { transform:translateX(160px); }
        #evaluationPreviewModal.dysign-eval-overlay { padding:8px; }
        .dysign-eval-dialog { height:calc(100dvh - 16px); border-radius:15px; }
        .dysign-eval-dialog-header { padding:18px 16px; }
        .dysign-eval-dialog-body { padding:15px 12px; }
        .dysign-eval-dialog-footer { padding:12px 15px; }
        .dysign-eval-questions > div { padding:15px 12px 15px 49px !important; }
        .dysign-eval-questions > div::before { left:10px; top:14px; }
    }
</style>



<div class="min-w-0 space-y-8">

    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <section
        class="
            dysign-eval-hero
            relative
            overflow-hidden
            rounded-[28px]
            bg-[#101064]
            px-7
            py-8
            text-white
            shadow-sm
            sm:px-8
            lg:px-10
        "
    >
        <div class="dysign-eval-hero-rings" aria-hidden="true">
            <span class="dysign-eval-ring"></span>
            <span class="dysign-eval-ring"></span>
            <span class="dysign-eval-ring"></span>
        </div>

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
                gap-5
                lg:flex-row
                lg:items-center
                lg:justify-between
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.30em]
                        text-[#E7C75B]
                    "
                >
                    Event Feedback & Insights
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
                    Event Evaluation Form Management
                </h1>


                <p
                    class="
                        mt-3
                        max-w-3xl
                        text-sm
                        leading-6
                        text-white/70
                    "
                >
                    Upload a normal Word questionnaire. DySign detects questions,
                    prepares a review preview, and makes the final evaluation
                    available only to verified event attendees.
                </p>
                <a href="#saved-evaluation-forms" class="mt-4 inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-[#D4A017]">
                    Browse saved questionnaires <span aria-hidden="true">↓</span>
                </a>

            </div>


            <div
                class="
                    rounded-2xl
                    border
                    border-white/15
                    bg-white/10
                    px-5
                    py-4
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
                    Automation
                </p>

                <p
                    class="
                        mt-1
                        text-sm
                        font-semibold
                    "
                >
                    Word → DySign Form
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
    {{-- IMPORT --}}
    {{-- ====================================================== --}}

    <section
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
                border-b
                border-gray-100
                px-6
                py-5
            "
        >

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.24em]
                    text-[#D4A017]
                "
            >
                Automatic Form Generator
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Import Evaluation Questionnaire
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                No special tags required. Write your questions as you normally would in Microsoft Word (.docx).
            </p>

        </div>


        <form
            id="importForm"
            enctype="multipart/form-data"
            class="
                grid
                grid-cols-1
                gap-6
                p-6
                xl:grid-cols-2
            "
        >

            {{-- EVENT --}}

            <div class="xl:col-span-2">

                <label
                    for="event_id"
                    class="
                        mb-2
                        block
                        text-sm
                        font-semibold
                        text-gray-700
                    "
                >
                    Event
                    <span class="text-red-500">*</span>
                </label>


                <select
                    id="event_id"
                    name="event_id"
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
                        focus:border-[#D4A017]
                        focus:ring-2
                        focus:ring-[#D4A017]/20
                    "
                >

                    <option value="">
                        Select event
                    </option>


                    @foreach($events as $event)

                        <option
                            value="{{ $event->event_id }}"
                        >
                            {{ $event->event_name }}
                            —
                            {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
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
                        No available events. An event may already have an
                        active evaluation form.
                    </p>

                @endif

            </div>



            {{-- OPTIONAL TITLE --}}

            <div>

                <label
                    for="title"
                    class="
                        mb-2
                        block
                        text-sm
                        font-semibold
                        text-gray-700
                    "
                >
                    Form Title
                </label>


                <input
                    type="text"
                    id="title"
                    name="title"
                    maxlength="255"
                    placeholder="Optional — can be read from Word file"
                    class="
                        w-full
                        rounded-xl
                        border
                        border-gray-300
                        px-4
                        py-3
                        text-sm
                        outline-none
                        focus:border-[#D4A017]
                        focus:ring-2
                        focus:ring-[#D4A017]/20
                    "
                >

            </div>



            {{-- FILE --}}

            <div>

                <label
                    for="source_file"
                    class="
                        mb-2
                        block
                        text-sm
                        font-semibold
                        text-gray-700
                    "
                >
                    Word Questionnaire
                    <span class="text-red-500">*</span>
                </label>


                <input
                    type="file"
                    id="source_file"
                    name="source_file"
                    accept=".docx"
                    required
                    class="
                        block
                        w-full
                        rounded-xl
                        border
                        border-gray-300
                        bg-white
                        px-4
                        py-3
                        text-sm
                        text-gray-600
                    "
                >


                <p
                    class="
                        mt-2
                        text-xs
                        text-gray-400
                    "
                >
                    Accepted format: .docx — maximum 10 MB.
                </p>

            </div>



            {{-- DESCRIPTION --}}

            <div class="xl:col-span-2">

                <label
                    for="description"
                    class="
                        mb-2
                        block
                        text-sm
                        font-semibold
                        text-gray-700
                    "
                >
                    Description
                </label>


                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    maxlength="5000"
                    placeholder="Optional — can also be read from the Word document"
                    class="
                        w-full
                        resize-none
                        rounded-xl
                        border
                        border-gray-300
                        px-4
                        py-3
                        text-sm
                        outline-none
                        focus:border-[#D4A017]
                        focus:ring-2
                        focus:ring-[#D4A017]/20
                    "
                ></textarea>

            </div>



            {{-- OPTIONAL FORMATTING TIPS: CLOSED BY DEFAULT --}}
            <details class="xl:col-span-2 group rounded-2xl border border-indigo-100 bg-[#F7F8FE]">
                <summary class="flex cursor-pointer list-none items-center justify-between gap-4 px-4 py-4 sm:px-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D4A017] [&::-webkit-details-marker]:hidden">
                    <span class="min-w-0">
                        <span class="block text-[10px] font-extrabold uppercase tracking-[0.2em] text-[#D4A017]">Optional help</span>
                        <span class="mt-1 block text-sm font-bold text-[#101064]">Word formatting tips & sample questions</span>
                        <span class="mt-1 block text-xs text-slate-500">Regular .docx questionnaire, no special codes required</span>
                    </span>
                    <span class="shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-[#101064] group-open:hidden">Show guide +</span>
                    <span class="hidden shrink-0 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-[#101064] group-open:inline-flex">Hide guide −</span>
                </summary>
                <div class="border-t border-indigo-100 px-4 pb-5 pt-4 sm:px-5">
                    <p class="text-xs leading-6 text-slate-600">Number questions or end them with a question mark. Put multiple-choice answers on separate lines (A, B, C or bullets). DySign detects common text, rating and selection questions. Verify the detected questions before generating.</p>
                    <div class="mt-3 rounded-xl border border-slate-200 bg-white p-4 text-xs leading-6 text-slate-700">
                        <p class="font-bold text-[#101064]">Example questionnaire</p>
                        <p class="mt-2">1. How satisfied were you with the event? (Rate 1–5)</p>
                        <p>2. Would you join a similar event?</p>
                        <p class="pl-4 text-slate-500">A. Yes &nbsp; B. No</p>
                        <p>3. What improvements would you suggest?</p>
                    </div>
                    <p class="mt-3 text-xs font-semibold text-[#101064]">Upload → Analyze → Review → Confirm. Published forms are answered only by eligible students with finalized Present/Late attendance.</p>
                </div>
            </details>

            {{-- REVIEW DETECTED QUESTIONS BEFORE IMPORT --}}
            <div id="analysisSection" class="xl:col-span-2 hidden rounded-2xl border-2 border-[#101064]/20 bg-white p-5 sm:p-6" aria-live="polite">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-[#D4A017]">Question analysis</p>
                        <h3 class="mt-1 text-lg font-bold text-[#101064]">Review before generating</h3>
                        <p id="analysisSummary" class="mt-1 text-xs text-slate-500"></p>
                    </div>
                    <span class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-800">Needs your confirmation</span>
                </div>
                <div id="analysisQuestions" class="mt-4 grid gap-3 lg:grid-cols-2"></div>
                <p class="mt-4 text-xs font-medium text-slate-500">Review every question. To correct an interpretation, edit your .docx and re-upload it. Nothing has been saved yet.</p>
            </div>

            {{-- BUTTON --}}

            <div
                class="
                    xl:col-span-2
                    flex
                    justify-end
                "
            >

                <button
                    type="submit"
                    id="importButton"
                    class="
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
                    Analyze & Review Word File
                </button>

            </div>

        </form>

    </section>



    {{-- ====================================================== --}}
    {{-- GENERATED PREVIEW — OPENS ONLY ON CLICK --}}
    {{-- ====================================================== --}}

    <div id="evaluationPreviewModal" class="dysign-eval-overlay hidden" role="dialog" aria-modal="true" aria-labelledby="previewTitle" aria-hidden="true">
        <section id="previewSection" class="dysign-eval-dialog" role="document">
            <header class="dysign-eval-dialog-header">
                <div class="dysign-eval-dialog-top">
                    <div class="min-w-0">
                        <p class="text-[10px] font-extrabold uppercase tracking-[.22em] text-[#F3CC64]">Evaluation questionnaire preview</p>
                        <h2 id="previewTitle" class="dysign-eval-dialog-title">Questionnaire</h2>
                        <p id="previewEvent" class="dysign-eval-dialog-event"></p>
                    </div>
                    <button type="button" id="closeEvaluationPreview" class="dysign-eval-close" aria-label="Close questionnaire preview" title="Close">&times;</button>
                </div>
                <div class="dysign-eval-dialog-meta">
                    <span id="previewStatus" class="rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700">Draft</span>
                    <p id="autoPublishInfo"></p>
                </div>
            </header>
            <div class="dysign-eval-dialog-body" tabindex="0" aria-label="Questionnaire questions - scroll to review">
                <div id="previewDescription" class="mb-5 hidden rounded-xl border border-[#e8eaf1] bg-white px-5 py-4 text-sm leading-6 text-slate-600"></div>
                <div class="dysign-eval-intro">
                    <p class="text-[11px] font-bold uppercase tracking-[.18em] text-[#101064]">Questions & answer choices</p>
                    <span class="text-[11px] text-slate-500">Read-only staff preview</span>
                </div>
                <div id="questionPreview" class="dysign-eval-questions"></div>
            </div>
            <footer class="dysign-eval-dialog-footer">
                <span class="text-xs leading-5 text-slate-500">This preview does not submit student responses.</span>
                <button type="button" id="closeEvaluationPreviewFooter" class="dysign-eval-footer-close">Close preview</button>
            </footer>
        </section>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('evaluationPreviewModal');
        if (modal && modal.parentElement !== document.body) document.body.appendChild(modal);
        document.getElementById('closeEvaluationPreviewFooter')?.addEventListener('click', () => {
            document.getElementById('closeEvaluationPreview')?.click();
        });
    });
    </script>


    {{-- ====================================================== --}}
    {{-- EXISTING FORMS --}}
    {{-- ====================================================== --}}

    <section id="saved-evaluation-forms" class="scroll-mt-24 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[0.22em] text-[#D4A017]">Evaluation library</p>
                <h2 class="mt-1 text-xl font-bold text-[#101064]">Saved Questionnaires</h2>
                <p class="mt-1 text-xs text-slate-500">Browse generated forms. Questions only open when you choose View Questionnaire.</p>
            </div>
            <div class="flex items-center gap-2">
                <span id="formCount" class="rounded-full border border-indigo-100 bg-[#F3F4FF] px-3 py-1.5 text-xs font-bold text-[#101064]">Loading...</span>
                <button type="button" id="refreshEvaluationForms" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-[#101064] hover:bg-slate-50">Refresh</button>
            </div>
        </div>
        <div class="grid gap-3 border-b border-slate-100 bg-slate-50/70 p-4 sm:grid-cols-[minmax(0,1fr)_180px_110px] sm:items-end sm:p-5">
            <label class="block text-xs font-bold text-[#101064]">Search by title or event
                <input id="formSearch" type="search" placeholder="Find a questionnaire..." class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-normal text-slate-800 outline-none focus:border-[#D4A017] focus:ring-2 focus:ring-amber-100" />
            </label>
            <label class="block text-xs font-bold text-[#101064]">Status
                <select id="formStatusFilter" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-normal text-slate-800 outline-none focus:border-[#D4A017]">
                    <option value="all">All statuses</option>
                    <option value="Draft">Draft</option>
                    <option value="Scheduled">Scheduled</option>
                    <option value="Published">Published</option>
                    <option value="Closed">Closed</option>
                </select>
            </label>
            <label class="block text-xs font-bold text-[#101064]">Show
                <select id="formPerPage" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-normal text-slate-800 outline-none focus:border-[#D4A017]">
                    <option value="10">10 forms</option>
                    <option value="25">25 forms</option>
                    <option value="50">50 forms</option>
                </select>
            </label>
        </div>
        <div id="formLoading" class="px-6 py-10 text-center text-sm text-slate-500" role="status">Loading evaluation forms...</div>
        <div id="emptyForms" class="hidden px-6 py-12 text-center">
            <p class="font-bold text-[#101064]">No questionnaires found</p>
            <p class="mt-2 text-xs text-slate-500">After confirming a Word import, its evaluation will appear here.</p>
        </div>
        <div id="formList" class="hidden divide-y divide-slate-100"></div>
        <div id="formPagination" class="hidden items-center justify-between gap-3 border-t border-slate-100 bg-white px-5 py-4 sm:px-6">
            <span id="formPageInfo" class="text-xs text-slate-500">0 forms</span>
            <div class="flex items-center gap-2">
                <button type="button" id="formPrevious" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-[#101064] hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">Previous</button>
                <span id="formPageNumber" class="min-w-[65px] text-center text-xs font-bold text-[#101064]">1 / 1</span>
                <button type="button" id="formNext" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-[#101064] hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-40">Next</button>
            </div>
        </div>
    </section>

</div>


<script>
document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | ELEMENTS
    |--------------------------------------------------------------------------
    */

    const importForm =
        document.getElementById('importForm');

    const importButton =
        document.getElementById('importButton');

    const analysisSection = document.getElementById('analysisSection');
    const analysisQuestions = document.getElementById('analysisQuestions');
    const analysisSummary = document.getElementById('analysisSummary');
    let analysisApproved = false;

    // Changing the questionnaire invalidates its previous preview.
    importForm?.addEventListener('change', () => {
        analysisApproved = false;
        analysisSection?.classList.add('hidden');
        if (importButton) importButton.textContent = 'Analyze & Review Word File';
    });
    importForm?.addEventListener('input', () => {
        if (!analysisApproved) return;
        analysisApproved = false;
        analysisSection?.classList.add('hidden');
        if (importButton) importButton.textContent = 'Analyze & Review Word File';
    });


    const previewSection =
        document.getElementById('previewSection');

    const previewTitle =
        document.getElementById('previewTitle');

    const previewEvent =
        document.getElementById('previewEvent');

    const previewStatus =
        document.getElementById('previewStatus');

    const previewDescription =
        document.getElementById('previewDescription');

    const questionPreview =
        document.getElementById('questionPreview');

    const autoPublishInfo =
        document.getElementById('autoPublishInfo');

    const formLoading =
        document.getElementById('formLoading');

    const emptyForms =
        document.getElementById('emptyForms');

    const formList =
        document.getElementById('formList');

    const formCount = document.getElementById('formCount');
    const formSearch = document.getElementById('formSearch');
    const formStatusFilter = document.getElementById('formStatusFilter');
    const formPerPage = document.getElementById('formPerPage');
    const formPagination = document.getElementById('formPagination');
    const formPageInfo = document.getElementById('formPageInfo');
    const formPageNumber = document.getElementById('formPageNumber');
    const formPrevious = document.getElementById('formPrevious');
    const formNext = document.getElementById('formNext');
    const previewModal = document.getElementById('evaluationPreviewModal');
    const closePreviewButton = document.getElementById('closeEvaluationPreview');
    let formDirectory = [];
    let formDirectoryPage = 1;
    let previewReturnFocus = null;

    function closeEvaluationPreview() {
        if (!previewModal) return;
        previewModal.classList.add('hidden');
        previewModal.classList.remove('flex');
        previewModal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        previewReturnFocus?.focus?.();
    }
    closePreviewButton?.addEventListener('click', closeEvaluationPreview);
    previewModal?.addEventListener('click', event => {
        if (event.target === previewModal) closeEvaluationPreview();
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && previewModal && !previewModal.classList.contains('hidden')) {
            closeEvaluationPreview();
        }
    });



    /*
    |--------------------------------------------------------------------------
    | CSRF
    |--------------------------------------------------------------------------
    */

    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        )?.getAttribute('content');


    /*
    |--------------------------------------------------------------------------
    | HTML ESCAPE
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        if (
            value === null
            ||
            value === undefined
        ) {
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
    | NOTIFICATION
    |--------------------------------------------------------------------------
    */

    function showNotification(
        message,
        type = 'success'
    ) {

        const old =
            document.getElementById(
                'evaluationNotification'
            );

        if (old) {
            old.remove();
        }


        const box =
            document.createElement('div');


        box.id =
            'evaluationNotification';


        const successClasses =
            'border-green-200 bg-green-50 text-green-800';

        const errorClasses =
            'border-red-200 bg-red-50 text-red-800';


        box.className =
            `
                fixed
                right-6
                top-6
                z-[9999]
                max-w-md
                rounded-xl
                border
                px-5
                py-4
                shadow-lg
                ${type === 'success'
                    ? successClasses
                    : errorClasses}
            `;


        box.innerHTML =
            `
                <div class="flex items-start gap-3">

                    <div class="flex-1">

                        <p class="text-sm font-semibold">
                            ${
                                type === 'success'
                                    ? 'Success'
                                    : 'Unable to process request'
                            }
                        </p>

                        <p class="mt-1 text-sm">
                            ${escapeHtml(message)}
                        </p>

                    </div>

                    <button
                        type="button"
                        class="text-lg leading-none opacity-60 hover:opacity-100"
                    >
                        ×
                    </button>

                </div>
            `;


        box.querySelector('button')
            .addEventListener(
                'click',
                () => box.remove()
            );


        document.body.appendChild(
            box
        );


        setTimeout(
            () => {

                if (
                    document.body.contains(box)
                ) {
                    box.remove();
                }

            },
            5000
        );
    }


    /*
    |--------------------------------------------------------------------------
    | API ERROR
    |--------------------------------------------------------------------------
    */

    function getErrorMessage(
        payload,
        fallback = 'Something went wrong.'
    ) {

        if (payload?.message) {
            return payload.message;
        }


        if (payload?.errors) {

            const firstError =
                Object.values(
                    payload.errors
                )
                .flat()
                .find(Boolean);


            if (firstError) {
                return firstError;
            }

        }


        return fallback;
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS BADGE
    |--------------------------------------------------------------------------
    */

    function getStatusClass(status) {

        switch (status) {

            case 'Published':

                return 'bg-green-50 text-green-700';


            case 'Scheduled':

                return 'bg-blue-50 text-blue-700';


            case 'Closed':

                return 'bg-gray-100 text-gray-600';


            case 'Draft':

            default:

                return 'bg-orange-50 text-orange-700';

        }
    }


    /*
    |--------------------------------------------------------------------------
    | QUESTION PREVIEW
    |--------------------------------------------------------------------------
    */

    function renderQuestion(question) {

        const required =
            question.is_required
                ? `
                    <span class="text-red-500">
                        *
                    </span>
                `
                : '';


        let answerArea = '';


        /*
        |--------------------------------------------------------------------------
        | SHORT ANSWER
        |--------------------------------------------------------------------------
        */

        if (
            question.question_type
            === 'Short Answer'
        ) {

            answerArea =
                `
                    <input
                        type="text"
                        disabled
                        placeholder="Short answer"
                        class="
                            mt-3
                            w-full
                            rounded-lg
                            border
                            border-gray-200
                            bg-gray-50
                            px-4
                            py-2.5
                            text-sm
                            text-gray-500
                        "
                    >
                `;

        }


        /*
        |--------------------------------------------------------------------------
        | PARAGRAPH
        |--------------------------------------------------------------------------
        */

        else if (
            question.question_type
            === 'Paragraph'
        ) {

            answerArea =
                `
                    <textarea
                        disabled
                        rows="3"
                        placeholder="Long answer"
                        class="
                            mt-3
                            w-full
                            rounded-lg
                            border
                            border-gray-200
                            bg-gray-50
                            px-4
                            py-2.5
                            text-sm
                            text-gray-500
                        "
                    ></textarea>
                `;

        }


        /*
        |--------------------------------------------------------------------------
        | YES / NO
        |--------------------------------------------------------------------------
        */

        else if (
            question.question_type
            === 'Yes No'
        ) {

            answerArea =
                `
                    <div class="mt-3 space-y-2">

                        <label class="flex items-center gap-2 text-sm text-gray-600">

                            <input
                                type="radio"
                                disabled
                            >

                            Yes

                        </label>


                        <label class="flex items-center gap-2 text-sm text-gray-600">

                            <input
                                type="radio"
                                disabled
                            >

                            No

                        </label>

                    </div>
                `;

        }


        /*
        |--------------------------------------------------------------------------
        | SINGLE CHOICE
        |--------------------------------------------------------------------------
        */

        else if (
            question.question_type
            === 'Single Choice'
        ) {

            const options =
                question.options ?? [];


            answerArea =
                `
                    <div class="mt-3 space-y-2">

                        ${
                            options.map(
                                option => `
                                    <label
                                        class="
                                            flex
                                            items-center
                                            gap-2
                                            text-sm
                                            text-gray-600
                                        "
                                    >

                                        <input
                                            type="radio"
                                            disabled
                                        >

                                        ${escapeHtml(
                                            option.option_text
                                        )}

                                    </label>
                                `
                            ).join('')
                        }

                    </div>
                `;

        }


        /*
        |--------------------------------------------------------------------------
        | MULTIPLE CHOICE
        |--------------------------------------------------------------------------
        */

        else if (
            question.question_type
            === 'Multiple Choice'
        ) {

            const options =
                question.options ?? [];


            answerArea =
                `
                    <div class="mt-3 space-y-2">

                        ${
                            options.map(
                                option => `
                                    <label
                                        class="
                                            flex
                                            items-center
                                            gap-2
                                            text-sm
                                            text-gray-600
                                        "
                                    >

                                        <input
                                            type="checkbox"
                                            disabled
                                        >

                                        ${escapeHtml(
                                            option.option_text
                                        )}

                                    </label>
                                `
                            ).join('')
                        }

                    </div>
                `;

        }


        /*
        |--------------------------------------------------------------------------
        | RATING
        |--------------------------------------------------------------------------
        */

        else if (
            question.question_type
            === 'Rating'
        ) {

            const min =
                Number(
                    question.rating_min ?? 1
                );

            const max =
                Number(
                    question.rating_max ?? 5
                );


            let ratingButtons =
                '';


            for (
                let value = min;
                value <= max;
                value++
            ) {

                ratingButtons +=
                    `
                        <span
                            class="
                                flex
                                h-9
                                w-9
                                items-center
                                justify-center
                                rounded-full
                                border
                                border-gray-200
                                bg-gray-50
                                text-sm
                                font-semibold
                                text-gray-600
                            "
                        >
                            ${value}
                        </span>
                    `;

            }


            answerArea =
                `
                    <div class="mt-3">

                        <div class="flex flex-wrap gap-2">
                            ${ratingButtons}
                        </div>


                        ${
                            question.rating_min_label
                            ||
                            question.rating_max_label

                                ? `
                                    <div
                                        class="
                                            mt-2
                                            flex
                                            justify-between
                                            text-xs
                                            text-gray-400
                                        "
                                    >

                                        <span>
                                            ${
                                                escapeHtml(
                                                    question.rating_min_label
                                                )
                                            }
                                        </span>

                                        <span>
                                            ${
                                                escapeHtml(
                                                    question.rating_max_label
                                                )
                                            }
                                        </span>

                                    </div>
                                `
                                : ''
                        }

                    </div>
                `;

        }


        return `
            <div
                class="
                    rounded-xl
                    border
                    border-gray-200
                    bg-white
                    p-5
                "
            >

                <div
                    class="
                        flex
                        items-start
                        justify-between
                        gap-4
                    "
                >

                    <div>

                        <p
                            class="
                                font-semibold
                                text-[#11175A]
                            "
                        >
                            ${escapeHtml(
                                question.question_text
                            )}

                            ${required}
                        </p>

                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                            "
                        >
                            ${escapeHtml(
                                question.question_type
                            )}
                        </p>

                    </div>

                </div>

                ${answerArea}

            </div>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | PREVIEW
    |--------------------------------------------------------------------------
    */

    function renderPreview(form) {

        if (!form) {
            return;
        }


        if (previewModal) {
            previewReturnFocus = document.activeElement;
            previewModal.classList.remove('hidden');
            previewModal.classList.add('flex');
            previewModal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }


        if (previewTitle) {

            previewTitle.textContent =
                form.title
                ||
                'Untitled Evaluation';

        }


        if (previewEvent) {

            previewEvent.textContent =
                form.event?.event_name
                ||
                'Event';

        }


        if (previewStatus) {

            previewStatus.textContent =
                form.status;


            previewStatus.className =
                `
                    rounded-full
                    px-3
                    py-1.5
                    text-xs
                    font-semibold
                    ${getStatusClass(
                        form.status
                    )}
                `;

        }


        if (previewDescription) {

            if (form.description) {

                previewDescription.textContent =
                    form.description;


                previewDescription.classList.remove(
                    'hidden'
                );

            }
            else {

                previewDescription.textContent =
                    'No description provided.';


                previewDescription.classList.remove(
                    'hidden'
                );

            }

        }


        if (autoPublishInfo) {

            switch (form.status) {

                case 'Scheduled':

                    autoPublishInfo.textContent =
                        'This evaluation will automatically become available to students after the event ends.';

                    break;


                case 'Published':

                    autoPublishInfo.textContent =
                        'Published automatically after the event ended and is now available to applicable students.';

                    break;


                case 'Closed':

                    autoPublishInfo.textContent =
                        'This evaluation form is already closed.';

                    break;


                default:

                    autoPublishInfo.textContent =
                        'Evaluation form is being prepared.';

            }

        }


        if (questionPreview) {

            const questions =
                form.questions ?? [];


            if (
                questions.length === 0
            ) {

                questionPreview.innerHTML =
                    `
                        <div
                            class="
                                rounded-xl
                                border
                                border-dashed
                                border-gray-300
                                p-6
                                text-center
                                text-sm
                                text-gray-500
                            "
                        >
                            No questions found.
                        </div>
                    `;

            }
            else {

                questionPreview.innerHTML =
                    questions
                        .map(
                            renderQuestion
                        )
                        .join('');

            }

        }


        closePreviewButton?.focus();
    }


    /*
    |--------------------------------------------------------------------------
    | EXISTING FORM CARD
    |--------------------------------------------------------------------------
    */

    function renderFormCard(form) {
        const questionCount = Number(form.questions_count ?? form.questions?.length ?? 0);
        const title = form.title || 'Untitled Questionnaire';
        const eventName = form.event?.event_name || 'Unknown Event';
        const id = Number(form.evaluation_form_id);
        const statuses = {
            Scheduled: 'Available after the event ends',
            Published: 'Available to verified attendees',
            Closed: 'Closed for submissions',
            Draft: 'Not yet available to students'
        };
        return `
            <article class="flex flex-col gap-3 px-5 py-4 hover:bg-[#F9FAFF] sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold ${getStatusClass(form.status)}">${escapeHtml(form.status || 'Draft')}</span>
                        <span class="rounded-full border border-indigo-100 bg-indigo-50 px-2.5 py-1 text-[10px] font-bold text-[#101064]">${questionCount} ${questionCount === 1 ? 'question' : 'questions'}</span>
                    </div>
                    <h3 class="mt-2 truncate text-sm font-extrabold text-[#101064]">${escapeHtml(title)}</h3>
                    <p class="mt-1 text-xs text-slate-500">${escapeHtml(eventName)}</p>
                    <p class="mt-1 text-[11px] text-slate-500">${escapeHtml(statuses[form.status] || 'Staff review only')}</p>
                </div>
                <button type="button" data-view-form="${Number.isSafeInteger(id) ? id : ''}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#101064] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#D4A017] hover:text-[#101064] focus:outline-none focus:ring-2 focus:ring-[#D4A017]">View Questionnaire <span aria-hidden="true">→</span></button>
            </article>
        `;
    }

    function renderFormDirectory() {
        const q = (formSearch?.value || '').trim().toLowerCase();
        const status = formStatusFilter?.value || 'all';
        const pageSize = Number(formPerPage?.value || 10);
        const matching = formDirectory.filter(form => {
            const text = `${form.title || ''} ${form.event?.event_name || ''}`.toLowerCase();
            return (!q || text.includes(q)) && (status === 'all' || form.status === status);
        });
        const totalPages = Math.max(1, Math.ceil(matching.length / pageSize));
        formDirectoryPage = Math.min(Math.max(1, formDirectoryPage), totalPages);
        const start = (formDirectoryPage - 1) * pageSize;
        const visibleForms = matching.slice(start, start + pageSize);

        if (formCount) formCount.textContent = `${formDirectory.length} saved ${formDirectory.length === 1 ? 'form' : 'forms'}`;
        if (formList) {
            formList.innerHTML = visibleForms.map(renderFormCard).join('');
            formList.classList.toggle('hidden', visibleForms.length === 0);
        }
        if (emptyForms) {
            emptyForms.classList.toggle('hidden', matching.length !== 0);
            const message = emptyForms.querySelector('p');
            if (message) message.textContent = formDirectory.length ? 'No matching questionnaires' : 'No saved questionnaires yet';
        }
        if (formPagination) {
            formPagination.classList.toggle('hidden', matching.length === 0);
            formPagination.classList.toggle('flex', matching.length !== 0);
        }
        if (formPageInfo) formPageInfo.textContent = matching.length ? `Showing ${start + 1}–${Math.min(start + pageSize, matching.length)} of ${matching.length}` : '0 forms';
        if (formPageNumber) formPageNumber.textContent = `${formDirectoryPage} / ${totalPages}`;
        if (formPrevious) formPrevious.disabled = formDirectoryPage <= 1;
        if (formNext) formNext.disabled = formDirectoryPage >= totalPages;
    }

    [formSearch, formStatusFilter, formPerPage].forEach(control => {
        control?.addEventListener(control === formSearch ? 'input' : 'change', () => {
            formDirectoryPage = 1;
            renderFormDirectory();
        });
    });
    formPrevious?.addEventListener('click', () => {
        if (formDirectoryPage > 1) { formDirectoryPage -= 1; renderFormDirectory(); }
    });
    formNext?.addEventListener('click', () => {
        formDirectoryPage += 1;
        renderFormDirectory();
    });
    document.getElementById('refreshEvaluationForms')?.addEventListener('click', () => loadForms());

    /*
    |--------------------------------------------------------------------------
    | LOAD EXISTING FORMS
    |--------------------------------------------------------------------------
    */

    async function loadForms() {
        if (formLoading) {
            formLoading.classList.remove('hidden');
            formLoading.textContent = 'Loading evaluation forms...';
        }
        formList?.classList.add('hidden');
        formPagination?.classList.add('hidden');
        formPagination?.classList.remove('flex');
        emptyForms?.classList.add('hidden');
        try {
            const response = await fetch('/api/staff/evaluation-forms', {
                method: 'GET',
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' }
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(getErrorMessage(payload, 'Unable to load evaluation forms.'));
            formDirectory = Array.isArray(payload.data) ? payload.data : [];
            formDirectoryPage = 1;
            formLoading?.classList.add('hidden');
            renderFormDirectory();
        } catch (error) {
            console.error('Evaluation forms error:', error);
            formDirectory = [];
            if (formLoading) {
                formLoading.classList.remove('hidden');
                formLoading.textContent = `Could not load saved forms: ${error.message}. Use Refresh to retry.`;
            }
            if (formCount) formCount.textContent = 'Load failed';
        }
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW EXISTING FORM
    |--------------------------------------------------------------------------
    */

    formList?.addEventListener(
        'click',
        async event => {

            const button =
                event.target.closest(
                    '[data-view-form]'
                );


            if (!button) {
                return;
            }


            const formId =
                button.getAttribute(
                    'data-view-form'
                );


            button.disabled =
                true;


            const oldText =
                button.textContent;


            button.textContent =
                'Loading...';


            try {

                const response =
                    await fetch(
                        `/api/staff/evaluation-forms/${formId}`,
                        {
                            method:
                                'GET',

                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                const payload =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        getErrorMessage(
                            payload,
                            'Unable to open evaluation form.'
                        )
                    );

                }


                const form =
                    payload.data
                    ??
                    payload.form
                    ??
                    payload.evaluation_form;


                renderPreview(
                    form
                );

            }
            catch (error) {

                console.error(
                    error
                );


                showNotification(
                    error.message,
                    'error'
                );

            }
            finally {

                button.disabled =
                    false;


                button.textContent =
                    oldText;

            }

        }
    );


    function showQuestionAnalysis(parsed) {
        const questions = Array.isArray(parsed.questions) ? parsed.questions : [];
        if (analysisSummary) {
            analysisSummary.textContent = `${questions.length} questions detected. Confirm that the types and choices match your Word file.`;
        }
        if (analysisQuestions) {
            analysisQuestions.innerHTML = questions.map((question, index) => {
                const options = Array.isArray(question.options) ? question.options : [];
                const optionMarkup = options.length
                    ? `<ul class="mt-3 space-y-1 border-t border-slate-100 pt-3 text-xs text-slate-600">${options.map(option => `<li class="flex items-center gap-2"><span class="inline-block h-2 w-2 rounded-full border border-slate-400"></span>${escapeHtml(option)}</li>`).join('')}</ul>`
                    : '';
                return `<article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex items-center justify-between gap-2"><span class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Question ${index + 1}</span><span class="rounded-full bg-indigo-50 px-2 py-1 text-[10px] font-semibold text-[#101064]">${escapeHtml(question.question_type)}</span></div>
                    <p class="mt-2 text-sm font-semibold leading-6 text-[#101064]">${escapeHtml(question.question_text)}</p>
                    ${question.question_type === 'Rating' ? `<p class="mt-2 text-xs text-slate-500">Scale: ${Number(question.rating_min || 1)}–${Number(question.rating_max || 5)}</p>` : ''}
                    ${optionMarkup}</article>`;
            }).join('');
        }
        analysisSection?.classList.remove('hidden');
        analysisSection?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /*
    |--------------------------------------------------------------------------
    | IMPORT WORD QUESTIONNAIRE
    |--------------------------------------------------------------------------
    */

    importForm?.addEventListener(
        'submit',
        async event => {

            event.preventDefault();


            const formData =
                new FormData(
                    importForm
                );

            const isConfirmedImport = analysisApproved;


            /*
            |--------------------------------------------------------------------------
            | BUTTON LOADING STATE
            |--------------------------------------------------------------------------
            */

            if (importButton) {

                importButton.disabled =
                    true;


                importButton.dataset.originalText =
                    importButton.textContent;


                importButton.textContent =
                    isConfirmedImport ? 'Generating Evaluation...' : 'Analyzing Word Document...';


                importButton.classList.add(
                    'opacity-60',
                    'cursor-not-allowed'
                );

            }


            try {

                const response =
                    await fetch(
                        isConfirmedImport ? '/api/staff/evaluation-forms/import' : '/api/staff/evaluation-forms/preview',
                        {
                            method:
                                'POST',

                            headers: {

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken

                            },

                            body:
                                formData
                        }
                    );


                const payload =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        getErrorMessage(
                            payload,
                            'Unable to import questionnaire.'
                        )
                    );

                }


                if (!isConfirmedImport) {
                    const parsed = payload.data || {};
                    showQuestionAnalysis(parsed);
                    analysisApproved = true;
                    showNotification('Review the detected questions, then click Confirm & Generate Form to continue.');
                    return;
                }

                const importedForm =
                    payload.data
                    ??
                    payload.form
                    ??
                    payload.evaluation_form;


                showNotification(
                    payload.message
                    ||
                    'Questionnaire imported and evaluation form generated successfully.'
                );


                /*
                |--------------------------------------------------------------------------
                | SHOW GENERATED PREVIEW
                |--------------------------------------------------------------------------
                */

                // Saved questionnaires appear in the library below.
                // Do not automatically expand every question after import.


                /*
                |--------------------------------------------------------------------------
                | CLEAR IMPORT FIELDS
                |--------------------------------------------------------------------------
                */

                importForm.reset();
                analysisApproved = false;
                analysisSection?.classList.add('hidden');


                /*
                |--------------------------------------------------------------------------
                | REFRESH EXISTING FORMS
                |--------------------------------------------------------------------------
                */

                await loadForms();
                document.getElementById('saved-evaluation-forms')?.scrollIntoView({
                    behavior: 'smooth', block: 'start'
                });

            }
            catch (error) {

                console.error(
                    'Evaluation import error:',
                    error
                );


                showNotification(
                    error.message,
                    'error'
                );

            }
            finally {

                if (importButton) {

                    importButton.disabled =
                        false;


                    importButton.textContent = analysisApproved
                        ? 'Confirm & Generate Form'
                        : 'Analyze & Review Word File';


                    importButton.classList.remove(
                        'opacity-60',
                        'cursor-not-allowed'
                    );

                }

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | INITIAL LOAD
    |--------------------------------------------------------------------------
    */

    loadForms();

});
</script>


</x-admin-layout>