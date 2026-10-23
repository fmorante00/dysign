<x-admin-layout>

<div class="min-w-0 space-y-8">

    {{-- ====================================================== --}}
    {{-- HERO --}}
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
                gap-6
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
                    Module #17
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
                    Event Evaluations
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
                    Share your feedback for events you participated in.
                    Each evaluation can only be submitted once.
                </p>

            </div>


            <div
                class="
                    grid
                    grid-cols-2
                    gap-3
                "
            >

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
                            tracking-wider
                            text-[#E7C75B]
                        "
                    >
                        Available
                    </p>

                    <p
                        id="availableCount"
                        class="
                            mt-1
                            text-2xl
                            font-bold
                        "
                    >
                        0
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
                            tracking-wider
                            text-[#E7C75B]
                        "
                    >
                        Submitted
                    </p>

                    <p
                        id="submittedCount"
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
    {{-- AVAILABLE EVALUATIONS --}}
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
                Student Feedback
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Available Evaluations
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Published evaluations for your applicable events appear here.
            </p>

        </div>


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
                text-sm
                text-gray-500
            "
        >
            Loading evaluations...
        </div>


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
                No evaluations available
            </p>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-400
                "
            >
                Published event evaluations will appear here.
            </p>

        </div>


        <div
            id="evaluationList"
            class="
                hidden
                grid
                grid-cols-1
                gap-4
                lg:grid-cols-2
            "
        ></div>

    </section>



    {{-- ====================================================== --}}
    {{-- EVALUATION FORM --}}
    {{-- ====================================================== --}}

    <section
        id="evaluationSection"
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
                border-b
                border-gray-100
                px-6
                py-6
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    md:flex-row
                    md:items-start
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
                        Event Evaluation
                    </p>


                    <h2
                        id="formTitle"
                        class="
                            mt-2
                            text-2xl
                            font-bold
                            text-[#101064]
                        "
                    ></h2>


                    <p
                        id="formEvent"
                        class="
                            mt-2
                            text-sm
                            font-semibold
                            text-gray-500
                        "
                    ></p>

                </div>


                <span
                    id="formStatus"
                    class="
                        rounded-full
                        bg-green-50
                        px-3
                        py-1.5
                        text-xs
                        font-semibold
                        text-green-700
                    "
                >
                    Published
                </span>

            </div>


            <p
                id="formDescription"
                class="
                    mt-4
                    hidden
                    max-w-3xl
                    text-sm
                    leading-6
                    text-gray-600
                "
            ></p>

        </div>


        <form
            id="evaluationForm"
            class="
                space-y-5
                p-6
            "
        >

            <div
                id="questionContainer"
                class="
                    space-y-5
                "
            ></div>


            <div
                id="submittedNotice"
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
            >
                You have already submitted this evaluation.
            </div>


            <div
                id="submitArea"
                class="
                    flex
                    justify-end
                    border-t
                    border-gray-100
                    pt-6
                "
            >

                <button
                    type="submit"
                    id="submitButton"
                    class="
                        rounded-xl
                        bg-[#101064]
                        px-7
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
                    Submit Evaluation
                </button>

            </div>

        </form>

    </section>

</div>

<!-- ================================================= -->
<!-- VIEW MY RESPONSE MODAL -->
<!-- ================================================= -->

<div
    id="responseModal"
    class="
        fixed
        inset-0
        z-50
        hidden
        items-center
        justify-center
        bg-black/40
        p-4
    "
>

    <div
        class="
            max-h-[90vh]
            w-full
            max-w-3xl
            overflow-y-auto
            rounded-2xl
            bg-white
            shadow-2xl
        "
    >

        <!-- HEADER -->

        <div
            class="
                sticky
                top-0
                z-10
                flex
                items-center
                justify-between
                border-b
                border-gray-200
                bg-white
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
                        tracking-[0.18em]
                        text-[#D49A00]
                    "
                >
                    Submitted Response
                </p>

                <h2
                    id="responseModalTitle"
                    class="
                        mt-1
                        text-xl
                        font-bold
                        text-[#11175A]
                    "
                >
                    My Evaluation Response
                </h2>

                <p
                    id="responseSubmittedAt"
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                </p>

            </div>


            <button
                type="button"
                id="closeResponseModal"
                class="
                    flex
                    h-9
                    w-9
                    items-center
                    justify-center
                    rounded-full
                    text-xl
                    text-gray-500
                    transition
                    hover:bg-gray-100
                    hover:text-gray-900
                "
            >
                ×
            </button>

        </div>


        <!-- CONTENT -->

        <div
            id="responseAnswers"
            class="
                space-y-4
                p-6
            "
        >

            <p
                class="
                    text-center
                    text-sm
                    text-gray-500
                "
            >
                Loading response...
            </p>

        </div>

    </div>

</div>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        let evaluations =
            [];


        let currentForm =
            null;


        const loadingState =
            document.getElementById(
                'loadingState'
            );


        const emptyState =
            document.getElementById(
                'emptyState'
            );


        const evaluationList =
            document.getElementById(
                'evaluationList'
            );


        const availableCount =
            document.getElementById(
                'availableCount'
            );


        const submittedCount =
            document.getElementById(
                'submittedCount'
            );


        const evaluationSection =
            document.getElementById(
                'evaluationSection'
            );


        const evaluationForm =
            document.getElementById(
                'evaluationForm'
            );


        const formTitle =
            document.getElementById(
                'formTitle'
            );


        const formEvent =
            document.getElementById(
                'formEvent'
            );


        const formDescription =
            document.getElementById(
                'formDescription'
            );


        const questionContainer =
            document.getElementById(
                'questionContainer'
            );


        const submittedNotice =
            document.getElementById(
                'submittedNotice'
            );


        const submitArea =
            document.getElementById(
                'submitArea'
            );


        const submitButton =
            document.getElementById(
                'submitButton'
            );


        const successAlert =
            document.getElementById(
                'successAlert'
            );


        const errorAlert =
            document.getElementById(
                'errorAlert'
            );


        const responseModal =
            document.getElementById(
                'responseModal'
            );


        const responseModalTitle =
            document.getElementById(
                'responseModalTitle'
            );


        const responseSubmittedAt =
            document.getElementById(
                'responseSubmittedAt'
            );


        const responseAnswers =
            document.getElementById(
                'responseAnswers'
            );


        const closeResponseModal =
            document.getElementById(
                'closeResponseModal'
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
        | LOAD AVAILABLE EVALUATIONS
        |--------------------------------------------------------------------------
        */

        async function loadEvaluations() {

            loadingState.classList.remove(
                'hidden'
            );


            emptyState.classList.add(
                'hidden'
            );


            evaluationList.classList.add(
                'hidden'
            );


            try {

                const response =
                    await fetch(
                        '/api/student/evaluations',
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
                        getErrorMessage(
                            data
                        )
                    );

                }


                evaluations =
                    data.data
                    ?? [];


                updateSummary();


                if (
                    evaluations.length
                    === 0
                ) {

                    emptyState.classList.remove(
                        'hidden'
                    );


                    return;
                }


                evaluationList.innerHTML =
                    evaluations
                        .map(
                            renderEvaluationCard
                        )
                        .join('');


                evaluationList.classList.remove(
                    'hidden'
                );

            }
            catch (error) {

                showError(
                    error.message
                );

            }
            finally {

                loadingState.classList.add(
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

            const submitted =
                evaluations.filter(
                    form =>
                        form.already_submitted
                )
                .length;


            const available =
                evaluations.length
                - submitted;


            availableCount.textContent =
                available;


            submittedCount.textContent =
                submitted;

        }



        /*
        |--------------------------------------------------------------------------
        | EVALUATION CARD
        |--------------------------------------------------------------------------
        */

        function renderEvaluationCard(
            form
        ) {

            const submitted =
                Boolean(
                    form.already_submitted
                );


            const statusBadge =
                submitted
                    ? `
                        <span
                            class="
                                rounded-full
                                bg-green-50
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                text-green-700
                            "
                        >
                            Submitted
                        </span>
                    `
                    : `
                        <span
                            class="
                                rounded-full
                                bg-blue-50
                                px-3
                                py-1
                                text-xs
                                font-semibold
                                text-blue-700
                            "
                        >
                            Available
                        </span>
                    `;


            const actionButton =
                submitted
                    ? `
                        <button
                            type="button"
                            data-view-response="${form.evaluation_form_id}"
                            class="
                                w-full
                                rounded-xl
                                border
                                border-[#101064]
                                bg-white
                                px-5
                                py-3
                                text-sm
                                font-semibold
                                text-[#101064]
                                transition
                                hover:bg-[#101064]
                                hover:text-white
                            "
                        >
                            View My Response
                        </button>
                    `
                    : `
                        <button
                            type="button"
                            onclick="openEvaluation(${form.evaluation_form_id})"
                            class="
                                w-full
                                rounded-xl
                                bg-[#101064]
                                px-5
                                py-3
                                text-sm
                                font-semibold
                                text-white
                                transition
                                hover:bg-[#D4A017]
                                hover:text-[#101064]
                            "
                        >
                            Answer Evaluation
                        </button>
                    `;


            const questionCount =
                form.questions?.length
                ?? 0;


            return `
                <article
                    class="
                        flex
                        h-full
                        flex-col
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
                            items-start
                            justify-between
                            gap-4
                        "
                    >

                        ${statusBadge}


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
                            ${questionCount}
                            Questions
                        </span>

                    </div>


                    <h3
                        class="
                            mt-5
                            text-lg
                            font-bold
                            text-[#101064]
                        "
                    >
                        ${
                            escapeHtml(
                                form.title
                            )
                        }
                    </h3>


                    <p
                        class="
                            mt-2
                            text-sm
                            font-medium
                            text-gray-500
                        "
                    >
                        ${
                            escapeHtml(
                                form.event?.event_name
                                ?? 'Event'
                            )
                        }
                    </p>


                    ${
                        form.description
                            ? `
                                <p
                                    class="
                                        mt-3
                                        line-clamp-2
                                        text-sm
                                        leading-6
                                        text-gray-500
                                    "
                                >
                                    ${
                                        escapeHtml(
                                            form.description
                                        )
                                    }
                                </p>
                            `
                            : ''
                    }


                    <div
                        class="
                            mt-auto
                            pt-6
                        "
                    >

                        ${actionButton}

                    </div>

                </article>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | OPEN EVALUATION
        |--------------------------------------------------------------------------
        */

        window.openEvaluation =
            async function (
                formId
            ) {

                hideAlerts();


                try {

                    const response =
                        await fetch(
                            `/api/student/evaluations/${formId}`,
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
                            getErrorMessage(
                                data
                            )
                        );

                    }


                    currentForm =
                        data.data;


                    renderForm(
                        currentForm
                    );


                    window.scrollTo({

                        top:
                            evaluationSection.offsetTop
                            - 90,

                        behavior:
                            'smooth',

                    });

                }
                catch (error) {

                    showError(
                        error.message
                    );

                }

            };



        /*
        |--------------------------------------------------------------------------
        | RENDER FULL FORM
        |--------------------------------------------------------------------------
        */

        function renderForm(
            form
        ) {

            formTitle.textContent =
                form.title
                ?? 'Event Evaluation';


            formEvent.textContent =
                form.event?.event_name
                ?? 'Event';


            if (
                form.description
            ) {

                formDescription.textContent =
                    form.description;


                formDescription.classList.remove(
                    'hidden'
                );

            }
            else {

                formDescription.classList.add(
                    'hidden'
                );

            }


            const questions =
                form.questions
                ?? [];


            questionContainer.innerHTML =
                questions
                    .map(
                        (
                            question,
                            index
                        ) =>
                            renderQuestion(
                                question,
                                index,
                                form.already_submitted
                            )
                    )
                    .join('');


            if (
                form.already_submitted
            ) {

                submittedNotice.classList.remove(
                    'hidden'
                );


                submitArea.classList.add(
                    'hidden'
                );

            }
            else {

                submittedNotice.classList.add(
                    'hidden'
                );


                submitArea.classList.remove(
                    'hidden'
                );

            }


            evaluationSection.classList.remove(
                'hidden'
            );

        }



        /*
        |--------------------------------------------------------------------------
        | QUESTION RENDERER
        |--------------------------------------------------------------------------
        */

        function renderQuestion(
            question,
            index,
            disabled
        ) {

            const questionId =
                question.evaluation_question_id;


            const type =
                question.question_type;


            const requiredMark =
                question.is_required
                    ? `
                        <span class="text-red-500">
                            *
                        </span>
                    `
                    : '';


            const disabledAttribute =
                disabled
                    ? 'disabled'
                    : '';


            let inputHtml =
                '';


            /*
            |--------------------------------------------------------------------------
            | Short Answer
            |--------------------------------------------------------------------------
            */

            if (
                type === 'Short Answer'
            ) {

                inputHtml = `
                    <input
                        type="text"
                        data-answer-value
                        maxlength="1000"
                        ${disabledAttribute}
                        placeholder="Enter your answer"
                        class="
                            mt-4
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
                            disabled:bg-gray-100
                        "
                    >
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Paragraph
            |--------------------------------------------------------------------------
            */

            if (
                type === 'Paragraph'
            ) {

                inputHtml = `
                    <textarea
                        data-answer-value
                        rows="5"
                        maxlength="5000"
                        ${disabledAttribute}
                        placeholder="Enter your feedback"
                        class="
                            mt-4
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
                            focus:border-[#D4A017]
                            focus:ring-2
                            focus:ring-[#D4A017]/20
                            disabled:bg-gray-100
                        "
                    ></textarea>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Yes / No
            |--------------------------------------------------------------------------
            */

            if (
                type === 'Yes No'
            ) {

                inputHtml = `
                    <div
                        class="
                            mt-4
                            space-y-3
                        "
                    >

                        ${renderBooleanOption(
                            questionId,
                            '1',
                            'Yes',
                            disabledAttribute
                        )}

                        ${renderBooleanOption(
                            questionId,
                            '0',
                            'No',
                            disabledAttribute
                        )}

                    </div>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Single Choice
            |--------------------------------------------------------------------------
            */

            if (
                type === 'Single Choice'
            ) {

                inputHtml = `
                    <div
                        class="
                            mt-4
                            space-y-3
                        "
                    >

                        ${
                            (
                                question.options
                                ?? []
                            )
                            .map(
                                option =>
                                    renderSingleOption(
                                        questionId,
                                        option,
                                        disabledAttribute
                                    )
                            )
                            .join('')
                        }

                    </div>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Multiple Choice
            |--------------------------------------------------------------------------
            */

            if (
                type === 'Multiple Choice'
            ) {

                inputHtml = `
                    <div
                        class="
                            mt-4
                            space-y-3
                        "
                    >

                        ${
                            (
                                question.options
                                ?? []
                            )
                            .map(
                                option =>
                                    renderMultipleOption(
                                        option,
                                        disabledAttribute
                                    )
                            )
                            .join('')
                        }

                    </div>
                `;

            }


            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            if (
                type === 'Rating'
            ) {

                const minimum =
                    Number(
                        question.rating_min
                        ?? 1
                    );


                const maximum =
                    Number(
                        question.rating_max
                        ?? 5
                    );


                let ratingHtml =
                    '';


                for (
                    let value = minimum;
                    value <= maximum;
                    value++
                ) {

                    ratingHtml += `
                        <label
                            class="
                                cursor-pointer
                                text-center
                            "
                        >

                            <input
                                type="radio"
                                name="rating_${questionId}"
                                data-rating-value
                                value="${value}"
                                ${disabledAttribute}
                                class="peer sr-only"
                            >

                            <span
                                class="
                                    flex
                                    h-11
                                    w-11
                                    items-center
                                    justify-center
                                    rounded-full
                                    border
                                    border-gray-300
                                    bg-white
                                    text-sm
                                    font-bold
                                    text-gray-600
                                    transition
                                    peer-checked:border-[#101064]
                                    peer-checked:bg-[#101064]
                                    peer-checked:text-white
                                "
                            >
                                ${value}
                            </span>

                        </label>
                    `;

                }


                inputHtml = `
                    <div class="mt-5">

                        <div
                            class="
                                flex
                                flex-wrap
                                gap-3
                            "
                        >
                            ${ratingHtml}
                        </div>


                        <div
                            class="
                                mt-3
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
                                        ?? ''
                                    )
                                }
                            </span>


                            <span>
                                ${
                                    escapeHtml(
                                        question.rating_max_label
                                        ?? ''
                                    )
                                }
                            </span>

                        </div>

                    </div>
                `;

            }


            return `
                <div
                    class="
                        evaluation-question
                        rounded-2xl
                        border
                        border-gray-200
                        bg-white
                        p-5
                    "
                    data-question-id="${questionId}"
                    data-question-type="${escapeHtml(type)}"
                >

                    <div
                        class="
                            flex
                            items-start
                            gap-3
                        "
                    >

                        <span
                            class="
                                flex
                                h-7
                                min-w-7
                                items-center
                                justify-center
                                rounded-full
                                bg-[#101064]
                                px-2
                                text-xs
                                font-bold
                                text-white
                            "
                        >
                            ${index + 1}
                        </span>


                        <div
                            class="
                                min-w-0
                                flex-1
                            "
                        >

                            <p
                                class="
                                    font-semibold
                                    leading-6
                                    text-gray-800
                                "
                            >
                                ${
                                    escapeHtml(
                                        question.question_text
                                    )
                                }

                                ${requiredMark}
                            </p>


                            <span
                                class="
                                    mt-2
                                    inline-block
                                    rounded-md
                                    bg-[#F1F2FA]
                                    px-2
                                    py-1
                                    text-[10px]
                                    font-semibold
                                    uppercase
                                    tracking-wider
                                    text-[#101064]
                                "
                            >
                                ${
                                    escapeHtml(
                                        type
                                    )
                                }
                            </span>


                            ${inputHtml}

                        </div>

                    </div>

                </div>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | YES / NO OPTION
        |--------------------------------------------------------------------------
        */

        function renderBooleanOption(
            questionId,
            value,
            label,
            disabledAttribute
        ) {

            return `
                <label
                    class="
                        flex
                        cursor-pointer
                        items-center
                        gap-3
                        text-sm
                        text-gray-600
                    "
                >

                    <input
                        type="radio"
                        name="boolean_${questionId}"
                        value="${value}"
                        data-boolean-value
                        ${disabledAttribute}
                    >

                    ${escapeHtml(label)}

                </label>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | SINGLE CHOICE
        |--------------------------------------------------------------------------
        */

        function renderSingleOption(
            questionId,
            option,
            disabledAttribute
        ) {

            return `
                <label
                    class="
                        flex
                        cursor-pointer
                        items-center
                        gap-3
                        text-sm
                        text-gray-600
                    "
                >

                    <input
                        type="radio"
                        name="single_${questionId}"
                        data-option-id="${option.evaluation_question_option_id}"
                        ${disabledAttribute}
                    >

                    ${
                        escapeHtml(
                            option.option_text
                        )
                    }

                </label>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | MULTIPLE CHOICE
        |--------------------------------------------------------------------------
        */

        function renderMultipleOption(
            option,
            disabledAttribute
        ) {

            return `
                <label
                    class="
                        flex
                        cursor-pointer
                        items-center
                        gap-3
                        text-sm
                        text-gray-600
                    "
                >

                    <input
                        type="checkbox"
                        data-option-id="${option.evaluation_question_option_id}"
                        ${disabledAttribute}
                    >

                    ${
                        escapeHtml(
                            option.option_text
                        )
                    }

                </label>
            `;

        }



        /*
        |--------------------------------------------------------------------------
        | COLLECT ANSWERS
        |--------------------------------------------------------------------------
        */

        function collectAnswers() {

            const answers =
                [];


            document
                .querySelectorAll(
                    '.evaluation-question'
                )
                .forEach(
                    questionElement => {

                        const questionId =
                            Number(
                                questionElement.dataset.questionId
                            );


                        const type =
                            questionElement.dataset.questionType;


                        /*
                        |--------------------------------------------------------------------------
                        | Text
                        |--------------------------------------------------------------------------
                        */

                        if (
                            type === 'Short Answer'
                            ||
                            type === 'Paragraph'
                        ) {

                            const input =
                                questionElement.querySelector(
                                    '[data-answer-value]'
                                );


                            answers.push({

                                question_id:
                                    questionId,

                                value:
                                    input?.value
                                    ?? '',

                            });


                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Rating
                        |--------------------------------------------------------------------------
                        */

                        if (
                            type === 'Rating'
                        ) {

                            const selected =
                                questionElement.querySelector(
                                    '[data-rating-value]:checked'
                                );


                            answers.push({

                                question_id:
                                    questionId,

                                value:
                                    selected?.value
                                    ?? null,

                            });


                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Yes / No
                        |--------------------------------------------------------------------------
                        */

                        if (
                            type === 'Yes No'
                        ) {

                            const selected =
                                questionElement.querySelector(
                                    '[data-boolean-value]:checked'
                                );


                            answers.push({

                                question_id:
                                    questionId,

                                value:
                                    selected?.value
                                    ?? null,

                            });


                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Single Choice
                        |--------------------------------------------------------------------------
                        */

                        if (
                            type === 'Single Choice'
                        ) {

                            const selected =
                                questionElement.querySelector(
                                    '[data-option-id]:checked'
                                );


                            answers.push({

                                question_id:
                                    questionId,

                                option_ids:
                                    selected
                                        ? [
                                            Number(
                                                selected.dataset.optionId
                                            )
                                        ]
                                        : [],

                            });


                            return;
                        }


                        /*
                        |--------------------------------------------------------------------------
                        | Multiple Choice
                        |--------------------------------------------------------------------------
                        */

                        if (
                            type === 'Multiple Choice'
                        ) {

                            const selected =
                                Array.from(
                                    questionElement.querySelectorAll(
                                        '[data-option-id]:checked'
                                    )
                                )
                                .map(
                                    element =>
                                        Number(
                                            element.dataset.optionId
                                        )
                                );


                            answers.push({

                                question_id:
                                    questionId,

                                option_ids:
                                    selected,

                            });

                        }

                    }
                );


            return answers;

        }



        /*
        |--------------------------------------------------------------------------
        | SUBMIT EVALUATION
        |--------------------------------------------------------------------------
        */

        evaluationForm.addEventListener(
            'submit',
            async function (
                event
            ) {

                event.preventDefault();


                if (
                    !currentForm
                    ||
                    currentForm.already_submitted
                ) {

                    return;
                }


                hideAlerts();


                const answers =
                    collectAnswers();


                submitButton.disabled =
                    true;


                submitButton.textContent =
                    'Submitting...';


                try {

                    const response =
                        await fetch(
                            `/api/student/evaluations/${currentForm.evaluation_form_id}/responses`,
                            {

                                method:
                                    'POST',

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

                                        answers:
                                            answers,

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


                    successAlert.classList.remove(
                        'hidden'
                    );


                    currentForm.already_submitted =
                        true;


                    renderForm(
                        currentForm
                    );


                    await loadEvaluations();


                    window.scrollTo({

                        top: 0,

                        behavior:
                            'smooth',

                    });

                }
                catch (error) {

                    showError(
                        error.message
                    );

                }
                finally {

                    submitButton.disabled =
                        false;


                    submitButton.textContent =
                        'Submit Evaluation';

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | VIEW MY SUBMITTED RESPONSE
        |--------------------------------------------------------------------------
        */

        function formatResponseAnswer(
            answer
        ) {

            const value =
                answer.answer;


            if (
                Array.isArray(
                    value
                )
            ) {

                if (
                    value.length === 0
                ) {

                    return `
                        <span class="italic text-gray-400">
                            No answer
                        </span>
                    `;

                }


                return value
                    .map(
                        item => `
                            <span
                                class="
                                    mr-2
                                    mb-2
                                    inline-flex
                                    rounded-full
                                    bg-[#F1F2FA]
                                    px-3
                                    py-1
                                    text-sm
                                    font-medium
                                    text-[#101064]
                                "
                            >
                                ${escapeHtml(item)}
                            </span>
                        `
                    )
                    .join('');

            }


            if (
                value === null
                ||
                value === undefined
                ||
                value === ''
            ) {

                return `
                    <span class="italic text-gray-400">
                        No answer
                    </span>
                `;

            }


            return escapeHtml(
                value
            );

        }



        async function viewMyResponse(
            evaluationFormId
        ) {

            hideAlerts();


            responseModal.classList.remove(
                'hidden'
            );


            responseModal.classList.add(
                'flex'
            );


            responseModalTitle.textContent =
                'My Evaluation Response';


            responseSubmittedAt.textContent =
                '';


            responseAnswers.innerHTML =
                `
                    <div
                        class="
                            py-10
                            text-center
                            text-sm
                            text-gray-500
                        "
                    >
                        Loading your submitted response...
                    </div>
                `;


            try {

                const response =
                    await fetch(
                        `/api/student/evaluations/${evaluationFormId}/response`,
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
                        getErrorMessage(
                            data
                        )
                    );

                }


                const submission =
                    data.data;


                responseModalTitle.textContent =
                    submission.title
                    ?? 'My Evaluation Response';


                if (
                    submission.submitted_at
                ) {

                    responseSubmittedAt.textContent =
                        'Submitted: '
                        +
                        new Date(
                            submission.submitted_at
                        )
                        .toLocaleString(
                            'en-PH',
                            {
                                dateStyle:
                                    'medium',

                                timeStyle:
                                    'short',
                            }
                        );

                }


                const answers =
                    submission.answers
                    ?? [];


                if (
                    answers.length === 0
                ) {

                    responseAnswers.innerHTML =
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
                                No submitted answers found.
                            </div>
                        `;


                    return;

                }


                responseAnswers.innerHTML =
                    answers
                        .map(
                            (
                                answer,
                                index
                            ) => `
                                <div
                                    class="
                                        rounded-2xl
                                        border
                                        border-gray-200
                                        bg-gray-50
                                        p-5
                                    "
                                >

                                    <div
                                        class="
                                            flex
                                            items-start
                                            gap-3
                                        "
                                    >

                                        <span
                                            class="
                                                flex
                                                h-8
                                                min-w-8
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-[#101064]
                                                px-2
                                                text-xs
                                                font-bold
                                                text-white
                                            "
                                        >
                                            ${index + 1}
                                        </span>


                                        <div
                                            class="
                                                min-w-0
                                                flex-1
                                            "
                                        >

                                            <p
                                                class="
                                                    font-semibold
                                                    leading-6
                                                    text-[#101064]
                                                "
                                            >
                                                ${
                                                    escapeHtml(
                                                        answer.question
                                                    )
                                                }
                                            </p>


                                            <span
                                                class="
                                                    mt-2
                                                    inline-block
                                                    rounded-md
                                                    bg-white
                                                    px-2
                                                    py-1
                                                    text-[10px]
                                                    font-semibold
                                                    uppercase
                                                    tracking-wider
                                                    text-gray-500
                                                "
                                            >
                                                ${
                                                    escapeHtml(
                                                        answer.type
                                                    )
                                                }
                                            </span>


                                            <div
                                                class="
                                                    mt-4
                                                    text-sm
                                                    font-medium
                                                    leading-6
                                                    text-gray-700
                                                "
                                            >
                                                ${
                                                    formatResponseAnswer(
                                                        answer
                                                    )
                                                }
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            `
                        )
                        .join('');

            }
            catch (error) {

                responseAnswers.innerHTML =
                    `
                        <div
                            class="
                                rounded-xl
                                border
                                border-red-200
                                bg-red-50
                                p-5
                                text-sm
                                text-red-700
                            "
                        >
                            ${escapeHtml(
                                error.message
                            )}
                        </div>
                    `;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | RESPONSE BUTTON + MODAL EVENTS
        |--------------------------------------------------------------------------
        */

        evaluationList.addEventListener(
            'click',
            function (
                event
            ) {

                const button =
                    event.target.closest(
                        '[data-view-response]'
                    );


                if (!button) {

                    return;

                }


                const evaluationFormId =
                    button.getAttribute(
                        'data-view-response'
                    );


                viewMyResponse(
                    evaluationFormId
                );

            }
        );


        closeResponseModal.addEventListener(
            'click',
            function () {

                responseModal.classList.add(
                    'hidden'
                );


                responseModal.classList.remove(
                    'flex'
                );

            }
        );


        responseModal.addEventListener(
            'click',
            function (
                event
            ) {

                if (
                    event.target
                    === responseModal
                ) {

                    responseModal.classList.add(
                        'hidden'
                    );


                    responseModal.classList.remove(
                        'flex'
                    );

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | HELPERS
        |--------------------------------------------------------------------------
        */

        function getErrorMessage(
            data
        ) {

            if (
                data.errors
            ) {

                const messages =
                    Object.values(
                        data.errors
                    )
                    .flat();


                if (
                    messages.length > 0
                ) {

                    return messages[0];

                }

            }


            return data.message
                ?? 'Something went wrong.';
        }



        function showError(
            message
        ) {

            errorAlert.textContent =
                message;


            errorAlert.classList.remove(
                'hidden'
            );

        }



        function hideAlerts() {

            successAlert.classList.add(
                'hidden'
            );


            errorAlert.classList.add(
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

        loadEvaluations();

    }
);

</script>

</x-admin-layout>