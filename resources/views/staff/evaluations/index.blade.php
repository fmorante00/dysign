<x-admin-layout>

<div class="min-w-0 space-y-8">

    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <section
        class="
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
                    Module #16
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
                    Upload a structured Word questionnaire and let DySign
                    automatically convert it into an interactive student
                    evaluation form.
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
                Upload a structured Microsoft Word (.docx) file.
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



            {{-- GUIDE --}}

            <div
                class="
                    xl:col-span-2
                    rounded-xl
                    border
                    border-blue-100
                    bg-blue-50
                    p-5
                "
            >

                <p
                    class="
                        text-sm
                        font-bold
                        text-[#101064]
                    "
                >
                    Supported Word Question Types
                </p>


                <div
                    class="
                        mt-3
                        flex
                        flex-wrap
                        gap-2
                    "
                >

                    @foreach([
                        '[SHORT_ANSWER]',
                        '[PARAGRAPH]',
                        '[SINGLE_CHOICE]',
                        '[MULTIPLE_CHOICE]',
                        '[RATING]',
                        '[YES_NO]',
                    ] as $type)

                        <span
                            class="
                                rounded-lg
                                bg-white
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                text-[#101064]
                                shadow-sm
                            "
                        >
                            {{ $type }}
                        </span>

                    @endforeach

                </div>

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
                    Import & Generate Form
                </button>

            </div>

        </form>

    </section>



    {{-- ====================================================== --}}
    {{-- GENERATED PREVIEW --}}
    {{-- ====================================================== --}}

    <section
        id="previewSection"
        class="
            hidden
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
                gap-4
                border-b
                border-gray-100
                px-6
                py-5
                md:flex-row
                md:items-center
                md:justify-between
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-semibold
                        uppercase
                        tracking-[0.24em]
                        text-[#D4A017]
                    "
                >
                    Generated Preview
                </p>


                <h2
                    id="previewTitle"
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                ></h2>


                <p
                    id="previewEvent"
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                ></p>

            </div>


            <div
                class="
                    flex
                    items-center
                    gap-3
                "
            >

                <span
                    id="previewStatus"
                    class="
                        rounded-full
                        bg-orange-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-orange-700
                    "
                >
                    Draft
                </span>

                <p
                    id="autoPublishInfo"
                    class="
                        text-xs
                        font-medium
                        text-gray-500
                    "
                >
                </p>


            </div>

        </div>


        <div class="p-6">

            <div
                id="previewDescription"
                class="
                    mb-6
                    hidden
                    rounded-xl
                    bg-gray-50
                    px-5
                    py-4
                    text-sm
                    leading-6
                    text-gray-600
                "
            ></div>


            <div
                id="questionPreview"
                class="
                    space-y-5
                "
            ></div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- EXISTING FORMS --}}
    {{-- ====================================================== --}}

    <section>

        <div class="mb-4">

            <p
                class="
                    text-xs
                    font-semibold
                    uppercase
                    tracking-[0.24em]
                    text-[#D4A017]
                "
            >
                Evaluation Forms
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Existing Forms
            </h2>

        </div>


        <div
            id="formLoading"
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                px-6
                py-12
                text-center
                text-sm
                text-gray-500
            "
        >
            Loading evaluation forms...
        </div>


        <div
            id="emptyForms"
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
                No evaluation forms yet.
            </p>

        </div>


        <div
            id="formList"
            class="
                hidden
                space-y-4
            "
        ></div>

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


        if (previewSection) {

            previewSection.classList.remove(
                'hidden'
            );

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


        previewSection?.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }


    /*
    |--------------------------------------------------------------------------
    | EXISTING FORM CARD
    |--------------------------------------------------------------------------
    */

    function renderFormCard(form) {

        const questionCount =
            form.questions?.length ?? 0;


        const eventName =
            form.event?.event_name
            ??
            'Unknown Event';


        return `
            <div
                class="
                    rounded-2xl
                    border
                    border-gray-200
                    bg-white
                    p-5
                    shadow-sm
                "
            >

                <div
                    class="
                        flex
                        flex-col
                        gap-4
                        md:flex-row
                        md:items-center
                        md:justify-between
                    "
                >

                    <div class="min-w-0">

                        <div
                            class="
                                mb-3
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
                                    ${getStatusClass(
                                        form.status
                                    )}
                                "
                            >
                                ${escapeHtml(
                                    form.status
                                )}
                            </span>


                            <span
                                class="
                                    rounded-full
                                    bg-indigo-50
                                    px-3
                                    py-1
                                    text-xs
                                    font-semibold
                                    text-indigo-700
                                "
                            >
                                ${questionCount}
                                ${
                                    questionCount === 1
                                        ? 'Question'
                                        : 'Questions'
                                }
                            </span>

                        </div>


                        <h3
                            class="
                                text-base
                                font-bold
                                text-[#11175A]
                            "
                        >
                            ${escapeHtml(
                                form.title
                                ||
                                'Untitled Evaluation'
                            )}
                        </h3>


                        <p
                            class="
                                mt-1
                                text-sm
                                font-medium
                                text-gray-600
                            "
                        >
                            ${escapeHtml(
                                eventName
                            )}
                        </p>


                        ${
                            form.description
                                ? `
                                    <p
                                        class="
                                            mt-2
                                            text-sm
                                            text-gray-500
                                        "
                                    >
                                        ${escapeHtml(
                                            form.description
                                        )}
                                    </p>
                                `
                                : ''
                        }

                    </div>


                    <button
                        type="button"
                        data-view-form="${
                            form.evaluation_form_id
                        }"
                        class="
                            shrink-0
                            text-sm
                            font-semibold
                            text-[#D49A00]
                            transition
                            hover:text-[#11175A]
                        "
                    >
                        View Form →
                    </button>

                </div>

            </div>
        `;
    }


    /*
    |--------------------------------------------------------------------------
    | LOAD EXISTING FORMS
    |--------------------------------------------------------------------------
    */

    async function loadForms() {

        if (formLoading) {

            formLoading.classList.remove(
                'hidden'
            );

            formLoading.textContent =
                'Loading evaluation forms...';

        }


        if (emptyForms) {

            emptyForms.classList.add(
                'hidden'
            );

        }


        if (formList) {

            formList.innerHTML =
                '';

        }


        try {

            const response =
                await fetch(
                    '/api/staff/evaluation-forms',
                    {
                        method: 'GET',
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
                        'Unable to load evaluation forms.'
                    )
                );

            }


            const forms =
                Array.isArray(
                    payload.data
                )
                    ? payload.data
                    : [];


            if (formLoading) {

                formLoading.classList.add(
                    'hidden'
                );

            }


            if (
                forms.length === 0
            ) {

                if (emptyForms) {

                    emptyForms.classList.remove(
                        'hidden'
                    );

                }
                else if (formList) {

                    formList.innerHTML =
                        `
                            <div
                                class="
                                    rounded-xl
                                    border
                                    border-dashed
                                    border-gray-300
                                    p-8
                                    text-center
                                    text-sm
                                    text-gray-500
                                "
                            >
                                No evaluation forms found.
                            </div>
                        `;

                }


                return;
            }


            if (formList) {

                formList.innerHTML =
                    forms
                        .map(
                            renderFormCard
                        )
                        .join('');

            }

        }
        catch (error) {

            console.error(
                'Evaluation forms error:',
                error
            );


            if (formLoading) {

                formLoading.classList.remove(
                    'hidden'
                );


                formLoading.textContent =
                    error.message;

            }


            showNotification(
                error.message,
                'error'
            );

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
                    'Importing & Generating...';


                importButton.classList.add(
                    'opacity-60',
                    'cursor-not-allowed'
                );

            }


            try {

                const response =
                    await fetch(
                        '/api/staff/evaluation-forms/import',
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

                if (importedForm) {

                    renderPreview(
                        importedForm
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | CLEAR IMPORT FIELDS
                |--------------------------------------------------------------------------
                */

                importForm.reset();


                /*
                |--------------------------------------------------------------------------
                | REFRESH EXISTING FORMS
                |--------------------------------------------------------------------------
                */

                await loadForms();

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


                    importButton.textContent =
                        importButton.dataset.originalText
                        ||
                        'Import & Generate Form';


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