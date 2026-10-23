<x-admin-layout>

    <div class="mx-auto max-w-7xl">


        {{-- ====================================================== --}}
        {{-- PAGE HEADER --}}
        {{-- ====================================================== --}}

        <div
            class="
                mb-8
                flex
                flex-col
                gap-4
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >

            <div>

                <p
                    class="
                        text-xs
                        font-bold
                        uppercase
                        tracking-[0.25em]
                        text-[#D4A017]
                    "
                >
                    Student Portal
                </p>

                <h1
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-[#101064]
                    "
                >
                    My Announcements
                </h1>

                <p
                    class="
                        mt-2
                        text-sm
                        text-gray-500
                    "
                >
                    View event announcements and updates
                    intended for your account.
                </p>

            </div>


            <div
                id="announcementCount"
                class="
                    rounded-xl
                    border
                    border-gray-200
                    bg-white
                    px-5
                    py-3
                    text-sm
                    font-semibold
                    text-[#101064]
                    shadow-sm
                "
            >
                Loading...
            </div>

        </div>



        {{-- ====================================================== --}}
        {{-- LOADING STATE --}}
        {{-- ====================================================== --}}

        <div
            id="loadingState"
            class="
                rounded-2xl
                border
                border-gray-200
                bg-white
                p-10
                text-center
                shadow-sm
            "
        >

            <p
                class="
                    text-sm
                    font-medium
                    text-gray-500
                "
            >
                Loading announcements...
            </p>

        </div>



        {{-- ====================================================== --}}
        {{-- ERROR STATE --}}
        {{-- ====================================================== --}}

        <div
            id="errorState"
            class="
                hidden
                rounded-2xl
                border
                border-red-200
                bg-red-50
                p-6
                text-sm
                font-medium
                text-red-700
            "
        >
            Unable to load announcements.
        </div>



        {{-- ====================================================== --}}
        {{-- EMPTY STATE --}}
        {{-- ====================================================== --}}

        <div
            id="emptyState"
            class="
                hidden
                rounded-2xl
                border
                border-dashed
                border-gray-300
                bg-white
                p-12
                text-center
            "
        >

            <div
                class="
                    mx-auto
                    flex
                    h-14
                    w-14
                    items-center
                    justify-center
                    rounded-full
                    bg-[#101064]/10
                    text-2xl
                "
            >
                📢
            </div>

            <h2
                class="
                    mt-4
                    text-lg
                    font-bold
                    text-[#101064]
                "
            >
                No announcements yet
            </h2>

            <p
                class="
                    mt-2
                    text-sm
                    text-gray-500
                "
            >
                Event announcements intended for you
                will appear here.
            </p>

        </div>



        {{-- ====================================================== --}}
        {{-- ANNOUNCEMENT LIST --}}
        {{-- ====================================================== --}}

        <div
            id="announcementList"
            class="
                hidden
                space-y-5
            "
        >
        </div>


    </div>



    <script>

        document.addEventListener(
            'DOMContentLoaded',
            async function () {

                const loadingState =
                    document.getElementById(
                        'loadingState'
                    );

                const errorState =
                    document.getElementById(
                        'errorState'
                    );

                const emptyState =
                    document.getElementById(
                        'emptyState'
                    );

                const announcementList =
                    document.getElementById(
                        'announcementList'
                    );

                const announcementCount =
                    document.getElementById(
                        'announcementCount'
                    );


                try {

                    /*
                    |--------------------------------------------------------------------------
                    | CALL STUDENT ANNOUNCEMENT REST API
                    |--------------------------------------------------------------------------
                    */

                    const response =
                        await fetch(
                            '/api/student/announcements',
                            {
                                method:
                                    'GET',

                                credentials:
                                    'same-origin',

                                headers: {

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest',

                                }
                            }
                        );


                    const data =
                        await response.json();


                    loadingState
                        .classList
                        .add(
                            'hidden'
                        );


                    if (
                        !response.ok
                        || !data.success
                    ) {

                        throw new Error(
                            data.message
                            ?? 'Unable to retrieve announcements.'
                        );
                    }


                    announcementCount
                        .textContent =
                            `${data.count} Announcement${data.count === 1 ? '' : 's'}`;


                    /*
                    |--------------------------------------------------------------------------
                    | EMPTY
                    |--------------------------------------------------------------------------
                    */

                    if (
                        data.count === 0
                    ) {

                        emptyState
                            .classList
                            .remove(
                                'hidden'
                            );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DISPLAY ANNOUNCEMENTS
                    |--------------------------------------------------------------------------
                    */

                    announcementList
                        .classList
                        .remove(
                            'hidden'
                        );


                    data.data.forEach(
                        announcement => {

                            const eventName =
                                announcement
                                    .event
                                    ?.event_name
                                ?? 'Event';


                            const publishedDate =
                                announcement
                                    .created_at
                                ? new Date(
                                    announcement
                                        .created_at
                                )
                                .toLocaleString(
                                    'en-PH',
                                    {
                                        dateStyle:
                                            'medium',

                                        timeStyle:
                                            'short',
                                    }
                                )
                                : '';


                            const attachments =
                                announcement
                                    .attachments
                                ?? [];


                                const attachmentText =
                                    attachments.length > 0
                                        ? `
                                            <div
                                                class="
                                                    mt-4
                                                    border-t
                                                    border-gray-100
                                                    pt-4
                                                "
                                            >

                                                <p
                                                    class="
                                                        mb-3
                                                        text-xs
                                                        font-bold
                                                        uppercase
                                                        tracking-wider
                                                        text-gray-400
                                                    "
                                                >
                                                    Attachments
                                                </p>


                                                <div
                                                    class="
                                                        grid
                                                        grid-cols-1
                                                        gap-3
                                                        sm:grid-cols-2
                                                        lg:grid-cols-3
                                                    "
                                                >

                                                    ${attachments.map(
                                                        attachment => {

                                                            const attachmentUrl =
                                                                `/student/announcements/${
                                                                    announcement.announcement_id
                                                                }/attachments/${
                                                                    attachment.announcement_attachment_id
                                                                }`;


                                                            const mimeType =
                                                                attachment.mime_type
                                                                ?? '';


                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | IMAGE ATTACHMENT
                                                            |--------------------------------------------------------------------------
                                                            */

                                                            if (
                                                                mimeType.startsWith(
                                                                    'image/'
                                                                )
                                                            ) {

                                                                return `
                                                                    <a
                                                                        href="${attachmentUrl}"
                                                                        target="_blank"
                                                                        rel="noopener noreferrer"
                                                                        class="
                                                                            group
                                                                            overflow-hidden
                                                                            rounded-xl
                                                                            border
                                                                            border-gray-200
                                                                            bg-gray-50
                                                                            transition
                                                                            hover:border-[#D4A017]
                                                                            hover:shadow-sm
                                                                        "
                                                                    >

                                                                        <img
                                                                            src="${attachmentUrl}"
                                                                            alt="${escapeHtml(
                                                                                attachment.file_name
                                                                            )}"
                                                                            class="
                                                                                h-40
                                                                                w-full
                                                                                object-cover
                                                                                transition
                                                                                group-hover:scale-[1.02]
                                                                            "
                                                                        >


                                                                        <div
                                                                            class="
                                                                                flex
                                                                                items-center
                                                                                gap-2
                                                                                px-3
                                                                                py-3
                                                                            "
                                                                        >

                                                                            <span>
                                                                                📎
                                                                            </span>


                                                                            <span
                                                                                class="
                                                                                    truncate
                                                                                    text-xs
                                                                                    font-medium
                                                                                    text-[#101064]
                                                                                "
                                                                            >
                                                                                ${escapeHtml(
                                                                                    attachment.file_name
                                                                                )}
                                                                            </span>

                                                                        </div>

                                                                    </a>
                                                                `;

                                                            }


                                                            /*
                                                            |--------------------------------------------------------------------------
                                                            | PDF / OTHER FILE
                                                            |--------------------------------------------------------------------------
                                                            */

                                                            return `
                                                                <a
                                                                    href="${attachmentUrl}"
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    class="
                                                                        flex
                                                                        items-center
                                                                        gap-3
                                                                        rounded-xl
                                                                        border
                                                                        border-gray-200
                                                                        bg-gray-50
                                                                        px-4
                                                                        py-4
                                                                        transition
                                                                        hover:border-[#D4A017]
                                                                        hover:bg-[#FFF8E1]
                                                                    "
                                                                >

                                                                    <div
                                                                        class="
                                                                            flex
                                                                            h-10
                                                                            w-10
                                                                            shrink-0
                                                                            items-center
                                                                            justify-center
                                                                            rounded-lg
                                                                            bg-white
                                                                            text-lg
                                                                            shadow-sm
                                                                        "
                                                                    >
                                                                        ${
                                                                            mimeType === 'application/pdf'
                                                                                ? '📄'
                                                                                : '📎'
                                                                        }
                                                                    </div>


                                                                    <div class="min-w-0">

                                                                        <p
                                                                            class="
                                                                                truncate
                                                                                text-sm
                                                                                font-semibold
                                                                                text-[#101064]
                                                                            "
                                                                        >
                                                                            ${escapeHtml(
                                                                                attachment.file_name
                                                                            )}
                                                                        </p>


                                                                        <p
                                                                            class="
                                                                                mt-1
                                                                                text-xs
                                                                                text-gray-400
                                                                            "
                                                                        >
                                                                            ${
                                                                                mimeType === 'application/pdf'
                                                                                    ? 'View PDF'
                                                                                    : 'View attachment'
                                                                            }
                                                                        </p>

                                                                    </div>

                                                                </a>
                                                            `;

                                                        }
                                                    ).join('')}

                                                </div>

                                            </div>
                                        `
                                        : '';


                            announcementList
                                .insertAdjacentHTML(
                                    'beforeend',
                                    `
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
                                                    border-l-4
                                                    border-[#D4A017]
                                                    px-6
                                                    py-6
                                                "
                                            >

                                                <div
                                                    class="
                                                        flex
                                                        flex-col
                                                        gap-3
                                                        sm:flex-row
                                                        sm:items-start
                                                        sm:justify-between
                                                    "
                                                >

                                                    <div>

                                                        <p
                                                            class="
                                                                text-xs
                                                                font-bold
                                                                uppercase
                                                                tracking-[0.18em]
                                                                text-[#D4A017]
                                                            "
                                                        >
                                                            ${escapeHtml(
                                                                eventName
                                                            )}
                                                        </p>


                                                        <h2
                                                            class="
                                                                mt-2
                                                                text-xl
                                                                font-bold
                                                                text-[#101064]
                                                            "
                                                        >
                                                            ${escapeHtml(
                                                                announcement.subject
                                                            )}
                                                        </h2>

                                                    </div>


                                                    <span
                                                        class="
                                                            text-xs
                                                            text-gray-400
                                                        "
                                                    >
                                                        ${escapeHtml(
                                                            publishedDate
                                                        )}
                                                    </span>

                                                </div>


                                                <p
                                                    class="
                                                        mt-4
                                                        whitespace-pre-line
                                                        text-sm
                                                        leading-7
                                                        text-gray-600
                                                    "
                                                >
                                                    ${escapeHtml(
                                                        announcement.message
                                                    )}
                                                </p>


                                                ${attachmentText}

                                            </div>

                                        </article>
                                    `
                                );

                        }
                    );

                }
                catch (error) {

                    console.error(
                        error
                    );


                    loadingState
                        .classList
                        .add(
                            'hidden'
                        );


                    errorState
                        .classList
                        .remove(
                            'hidden'
                        );


                    errorState
                        .textContent =
                            error.message;

                }

            }
        );


        /*
        |--------------------------------------------------------------------------
        | BASIC HTML ESCAPING
        |--------------------------------------------------------------------------
        */

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

    </script>

</x-admin-layout>