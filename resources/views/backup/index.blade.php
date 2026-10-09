<x-admin-layout>

@php
    $backups = $backups ?? collect();
    $lastBackup = $lastBackup ?? null;
    $latestRecord = $latestRecord ?? null;
    $storageUsed = $storageUsed ?? '0 KB';
@endphp

<style>

    /*
    |--------------------------------------------------------------------------
    | HERO
    |--------------------------------------------------------------------------
    */

    .backup-hero {
        background:
            linear-gradient(
                100deg,
                rgba(16,16,100,.97) 0%,
                rgba(16,16,100,.92) 52%,
                rgba(16,16,100,.74) 100%
            ),
            url('{{ asset('images/school.jpg') }}');

        background-size: cover;
        background-position: center;
    }

</style>


<div class="min-w-0 space-y-8">


    {{-- ====================================================== --}}
    {{-- FLASH MESSAGES --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div
            class="
                flex
                items-start
                gap-3
                rounded-2xl
                border
                border-green-200
                bg-green-50
                px-5
                py-4
                text-sm
                text-green-700
            "
        >
            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
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

            <div>
                <p class="font-semibold">
                    Backup completed
                </p>

                <p class="mt-1 text-green-600">
                    {{ session('success') }}
                </p>
            </div>
        </div>

    @endif


    @if(session('error'))

        <div
            class="
                flex
                items-start
                gap-3
                rounded-2xl
                border
                border-red-200
                bg-red-50
                px-5
                py-4
                text-sm
                text-red-700
            "
        >
            <svg
                class="mt-0.5 h-5 w-5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M12 9v4m0 4h.01M10.3 3.6L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.6a2 2 0 00-3.4 0z"
                />
            </svg>

            <div>
                <p class="font-semibold">
                    Backup failed
                </p>

                <p class="mt-1 text-red-600">
                    {{ session('error') }}
                </p>
            </div>
        </div>

    @endif



    {{-- ====================================================== --}}
    {{-- HERO --}}
    {{-- ====================================================== --}}

    <section
        class="
            backup-hero
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
                gap-8
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
                    System Management
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
                    Backup Management
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
                    Monitor backup status, review backup history,
                    and manage protected copies of essential DySign records.
                </p>

            </div>


            <div
                class="
                    hidden
                    shrink-0
                    items-center
                    gap-4
                    rounded-2xl
                    border
                    border-white/15
                    bg-white/10
                    px-6
                    py-5
                    backdrop-blur-sm
                    lg:flex
                "
            >

                <div
                    class="
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-xl
                        bg-white/10
                        text-[#E7C75B]
                    "
                >

                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M4 7h16M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2zm4 8h4"
                        />
                    </svg>

                </div>


                <div>

                    <p
                        class="
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.22em]
                            text-[#E7C75B]
                        "
                    >
                        Backup Service
                    </p>


                    <p
                        class="
                            mt-1
                            text-sm
                            font-semibold
                            text-white
                        "
                    >
                        System Data Protection
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- BACKUP STATUS --}}
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
                Backup Overview
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Current Backup Status
            </h2>


            <p
                class="
                    mt-1
                    text-sm
                    text-gray-500
                "
            >
                Review the latest backup activity and storage information.
            </p>

        </div>


        <div
            class="
                grid
                grid-cols-1
                overflow-hidden
                border
                border-gray-200
                bg-white
                md:grid-cols-3
            "
        >


            {{-- LAST BACKUP --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-b-0
                    md:border-r
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
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-[0.16em]
                                text-gray-400
                            "
                        >
                            Last Backup
                        </p>


                        <p
                            class="
                                mt-2
                                text-xl
                                font-bold
                                text-[#101064]
                            "
                        >
                            @if($lastBackup)
                                {{ $lastBackup->created_at->format('F d, Y') }}
                            @else
                                No Backup Yet
                            @endif
                        </p>


                        <p
                            class="
                                mt-1
                                text-sm
                                text-gray-400
                            "
                        >
                            @if($lastBackup)
                                {{ $lastBackup->created_at->format('h:i A') }}
                            @else
                                Create your first backup
                            @endif
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#F1F2FA]
                            text-[#101064]
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>

                    </div>

                </div>

            </div>



            {{-- BACKUP STATUS --}}

            <div
                class="
                    border-b
                    border-gray-100
                    px-6
                    py-6
                    md:border-b-0
                    md:border-r
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
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-[0.16em]
                                text-gray-400
                            "
                        >
                            Backup Status
                        </p>


                        <div class="mt-3">

                            @if($latestRecord)

                                @if($latestRecord->status === 'Completed')

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            bg-green-50
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            text-green-700
                                        "
                                    >
                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-green-500
                                            "
                                        ></span>

                                        Completed
                                    </span>

                                @elseif($latestRecord->status === 'Processing')

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            bg-[#FFF8E1]
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            text-[#A87900]
                                        "
                                    >
                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                animate-pulse
                                                rounded-full
                                                bg-[#D4A017]
                                            "
                                        ></span>

                                        Processing
                                    </span>

                                @else

                                    <span
                                        class="
                                            inline-flex
                                            items-center
                                            gap-2
                                            rounded-full
                                            bg-red-50
                                            px-3
                                            py-1.5
                                            text-xs
                                            font-semibold
                                            text-red-600
                                        "
                                    >
                                        <span
                                            class="
                                                h-1.5
                                                w-1.5
                                                rounded-full
                                                bg-red-500
                                            "
                                        ></span>

                                        Failed
                                    </span>

                                @endif

                            @else

                                <span
                                    class="
                                        inline-flex
                                        rounded-full
                                        bg-gray-100
                                        px-3
                                        py-1.5
                                        text-xs
                                        font-semibold
                                        text-gray-500
                                    "
                                >
                                    No backups yet
                                </span>

                            @endif

                        </div>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            {{ $latestRecord?->status === 'Failed'
                                ? 'bg-red-50 text-red-600'
                                : ($latestRecord?->status === 'Processing'
                                    ? 'bg-[#FFF8E1] text-[#A87900]'
                                    : 'bg-green-50 text-green-600') }}
                        "
                    >

                        @if($latestRecord?->status === 'Failed')

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 9v4m0 4h.01M10.3 3.6L2.7 17a2 2 0 001.7 3h15.2a2 2 0 001.7-3L13.7 3.6a2 2 0 00-3.4 0z"
                                />
                            </svg>

                        @elseif($latestRecord?->status === 'Processing')

                            <svg
                                class="h-5 w-5 animate-spin"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 3a9 9 0 108.5 6"
                                />
                            </svg>

                        @else

                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                        @endif

                    </div>

                </div>

            </div>



            {{-- STORAGE USED --}}

            <div class="px-6 py-6">

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
                                text-[10px]
                                font-bold
                                uppercase
                                tracking-[0.16em]
                                text-gray-400
                            "
                        >
                            Storage Used
                        </p>


                        <p
                            class="
                                mt-2
                                text-3xl
                                font-bold
                                tracking-tight
                                text-[#D4A017]
                            "
                        >
                            {{ $storageUsed }}
                        </p>


                        <p
                            class="
                                mt-1
                                text-xs
                                text-gray-400
                            "
                        >
                            Completed backup files only
                        </p>

                    </div>


                    <div
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#FFF8E1]
                            text-[#A87900]
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 6c0-1.66 3.58-3 8-3s8 1.34 8 3-3.58 3-8 3-8-1.34-8-3zm0 0v6c0 1.66 3.58 3 8 3s8-1.34 8-3V6m-16 6v6c0 1.66 3.58 3 8 3s8-1.34 8-3v-6"
                            />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- CREATE BACKUP --}}
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
                Backup Control
            </p>


            <h2
                class="
                    mt-2
                    text-xl
                    font-bold
                    text-[#101064]
                "
            >
                Create System Backup
            </h2>

        </div>


        <div
            class="
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            <div
                class="
                    flex
                    flex-col
                    gap-6
                    px-7
                    py-7
                    lg:flex-row
                    lg:items-center
                    lg:justify-between
                "
            >

                <div
                    class="
                        flex
                        items-start
                        gap-4
                    "
                >

                    <div
                        class="
                            flex
                            h-11
                            w-11
                            shrink-0
                            items-center
                            justify-center
                            rounded-xl
                            bg-[#F1F2FA]
                            text-[#101064]
                        "
                    >

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 5v14m7-7H5"
                            />
                        </svg>

                    </div>


                    <div>

                        <h3
                            class="
                                text-base
                                font-bold
                                text-[#101064]
                            "
                        >
                            Create New Backup
                        </h3>


                        <p
                            class="
                                mt-1
                                max-w-2xl
                                text-sm
                                leading-6
                                text-gray-500
                            "
                        >
                            Generate a protected SQL backup copy of the current
                            DySign database. The backup can be downloaded later
                            from the backup history.
                        </p>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('backup.store') }}"
                    onsubmit="handleBackupSubmit(this)"
                >
                    @csrf

                    <button
                        id="create-backup-button"
                        type="submit"
                        class="
                            inline-flex
                            shrink-0
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
                                d="M12 5v14m7-7H5"
                            />
                        </svg>

                        <span id="create-backup-text">
                            Create Backup
                        </span>

                    </button>

                </form>

            </div>

        </div>

    </section>



    {{-- ====================================================== --}}
    {{-- BACKUP HISTORY --}}
    {{-- ====================================================== --}}

    <section>

        <div
            class="
                mb-4
                flex
                flex-col
                gap-3
                sm:flex-row
                sm:items-end
                sm:justify-between
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
                    Backup Records
                </p>


                <h2
                    class="
                        mt-2
                        text-xl
                        font-bold
                        text-[#101064]
                    "
                >
                    Backup History
                </h2>


                <p
                    class="
                        mt-1
                        text-sm
                        text-gray-500
                    "
                >
                    Review and download previously generated system backups.
                </p>

            </div>


            <div
                class="
                    inline-flex
                    w-fit
                    items-center
                    gap-2
                    rounded-full
                    bg-[#F1F2FA]
                    px-3
                    py-1.5
                    text-xs
                    font-semibold
                    text-[#101064]
                "
            >

                <span
                    class="
                        h-1.5
                        w-1.5
                        rounded-full
                        bg-[#D4A017]
                    "
                ></span>

                @if(method_exists($backups, 'total'))
                    {{ number_format($backups->total()) }}
                    Backup{{ $backups->total() === 1 ? '' : 's' }}
                @else
                    {{ number_format($backups->count()) }}
                    Backup{{ $backups->count() === 1 ? '' : 's' }}
                @endif

            </div>

        </div>



        <div
            class="
                overflow-hidden
                border
                border-gray-200
                bg-white
            "
        >

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        min-w-[980px]
                        text-left
                    "
                >

                    <thead class="bg-gray-50">

                        <tr>

                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Date & Time
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Backup File
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Created By
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Status
                            </th>


                            <th
                                class="
                                    px-6
                                    py-4
                                    text-right
                                    text-[10px]
                                    font-bold
                                    uppercase
                                    tracking-[0.16em]
                                    text-gray-400
                                "
                            >
                                Action
                            </th>

                        </tr>

                    </thead>



                    <tbody class="divide-y divide-gray-100">

                        @forelse($backups as $backup)

                            @php

                                $creator =
                                    $backup->creator?->name
                                    ?? 'System';

                                $initials =
                                    collect(
                                        preg_split(
                                            '/\s+/',
                                            trim($creator)
                                        )
                                    )
                                    ->filter()
                                    ->take(2)
                                    ->map(
                                        fn ($word) =>
                                            strtoupper(
                                                substr(
                                                    $word,
                                                    0,
                                                    1
                                                )
                                            )
                                    )
                                    ->implode('');


                                $size = (int) ($backup->size_bytes ?? 0);

                                if ($size <= 0) {

                                    $formattedSize = '—';

                                } elseif ($size >= 1073741824) {

                                    $formattedSize =
                                        number_format(
                                            $size / 1073741824,
                                            2
                                        )
                                        . ' GB';

                                } elseif ($size >= 1048576) {

                                    $formattedSize =
                                        number_format(
                                            $size / 1048576,
                                            2
                                        )
                                        . ' MB';

                                } elseif ($size >= 1024) {

                                    $formattedSize =
                                        number_format(
                                            $size / 1024,
                                            2
                                        )
                                        . ' KB';

                                } else {

                                    $formattedSize =
                                        number_format($size)
                                        . ' B';

                                }

                            @endphp


                            <tr
                                class="
                                    transition
                                    hover:bg-gray-50/70
                                "
                            >

                                {{-- DATE & TIME --}}

                                <td class="px-6 py-5">

                                    <p
                                        class="
                                            whitespace-nowrap
                                            text-sm
                                            font-semibold
                                            text-gray-700
                                        "
                                    >
                                        {{ $backup->created_at->format('F d, Y') }}
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            whitespace-nowrap
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        {{ $backup->created_at->format('h:i A') }}
                                    </p>

                                </td>



                                {{-- BACKUP FILE --}}

                                <td class="px-6 py-5">

                                    <p
                                        class="
                                            max-w-[300px]
                                            truncate
                                            text-sm
                                            font-semibold
                                            text-[#101064]
                                        "
                                        title="{{ $backup->filename }}"
                                    >
                                        {{ $backup->filename }}
                                    </p>


                                    <p
                                        class="
                                            mt-1
                                            text-xs
                                            text-gray-400
                                        "
                                    >
                                        {{ $formattedSize }}
                                    </p>

                                </td>



                                {{-- CREATED BY --}}

                                <td class="px-6 py-5">

                                    <div
                                        class="
                                            flex
                                            items-center
                                            gap-3
                                        "
                                    >

                                        <div
                                            class="
                                                flex
                                                h-9
                                                w-9
                                                shrink-0
                                                items-center
                                                justify-center
                                                rounded-xl
                                                bg-[#F1F2FA]
                                                text-xs
                                                font-bold
                                                text-[#101064]
                                            "
                                        >
                                            {{ $initials ?: 'SY' }}
                                        </div>


                                        <span
                                            class="
                                                text-sm
                                                font-semibold
                                                text-[#101064]
                                            "
                                        >
                                            {{ $creator }}
                                        </span>

                                    </div>

                                </td>



                                {{-- STATUS --}}

                                <td class="px-6 py-5">

                                    @if($backup->status === 'Completed')

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-green-50
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-green-700
                                            "
                                        >
                                            <span
                                                class="
                                                    h-1.5
                                                    w-1.5
                                                    rounded-full
                                                    bg-green-500
                                                "
                                            ></span>

                                            Completed
                                        </span>

                                    @elseif($backup->status === 'Processing')

                                        <span
                                            class="
                                                inline-flex
                                                items-center
                                                gap-2
                                                rounded-full
                                                bg-[#FFF8E1]
                                                px-3
                                                py-1
                                                text-xs
                                                font-semibold
                                                text-[#A87900]
                                            "
                                        >
                                            <span
                                                class="
                                                    h-1.5
                                                    w-1.5
                                                    animate-pulse
                                                    rounded-full
                                                    bg-[#D4A017]
                                                "
                                            ></span>

                                            Processing
                                        </span>

                                    @else

                                        <div>

                                            <span
                                                class="
                                                    inline-flex
                                                    items-center
                                                    gap-2
                                                    rounded-full
                                                    bg-red-50
                                                    px-3
                                                    py-1
                                                    text-xs
                                                    font-semibold
                                                    text-red-600
                                                "
                                            >
                                                <span
                                                    class="
                                                        h-1.5
                                                        w-1.5
                                                        rounded-full
                                                        bg-red-500
                                                    "
                                                ></span>

                                                Failed
                                            </span>


                                            @if($backup->error_message)

                                                <p
                                                    class="
                                                        mt-2
                                                        max-w-[280px]
                                                        truncate
                                                        text-xs
                                                        text-red-400
                                                    "
                                                    title="{{ $backup->error_message }}"
                                                >
                                                    {{ $backup->error_message }}
                                                </p>

                                            @endif

                                        </div>

                                    @endif

                                </td>



                                {{-- ACTION --}}

                                <td
                                    class="
                                        px-6
                                        py-5
                                        text-right
                                    "
                                >

                                    @if($backup->status === 'Completed')

                                        <a
                                            href="{{ route(
                                                'backup.download',
                                                $backup->backup_id
                                            ) }}"
                                            class="
                                                inline-flex
                                                items-center
                                                justify-center
                                                gap-2
                                                rounded-xl
                                                border
                                                border-[#101064]/15
                                                bg-[#F1F2FA]
                                                px-4
                                                py-2
                                                text-xs
                                                font-semibold
                                                text-[#101064]
                                                transition
                                                hover:border-[#D4A017]
                                                hover:bg-[#FFF8E1]
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
                                                    d="M12 3v12m0 0l-4-4m4 4l4-4M5 21h14"
                                                />
                                            </svg>

                                            Download

                                        </a>

                                    @elseif($backup->status === 'Processing')

                                        <span
                                            class="
                                                text-xs
                                                font-medium
                                                text-gray-400
                                            "
                                        >
                                            Please wait
                                        </span>

                                    @else

                                        <span
                                            class="
                                                text-xs
                                                font-medium
                                                text-red-400
                                            "
                                        >
                                            Unavailable
                                        </span>

                                    @endif

                                </td>

                            </tr>


                        @empty

                            <tr>

                                <td
                                    colspan="5"
                                    class="
                                        px-6
                                        py-16
                                        text-center
                                    "
                                >

                                    <div
                                        class="
                                            mx-auto
                                            flex
                                            h-12
                                            w-12
                                            items-center
                                            justify-center
                                            rounded-xl
                                            bg-[#F1F2FA]
                                            text-[#101064]
                                        "
                                    >

                                        <svg
                                            class="h-6 w-6"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4 7h16M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2zm4 8h4"
                                            />
                                        </svg>

                                    </div>


                                    <p
                                        class="
                                            mt-4
                                            font-semibold
                                            text-[#101064]
                                        "
                                    >
                                        No backups created yet
                                    </p>


                                    <p
                                        class="
                                            mx-auto
                                            mt-1
                                            max-w-md
                                            text-sm
                                            leading-6
                                            text-gray-400
                                        "
                                    >
                                        Create your first database backup
                                        using the backup control above.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>



            {{-- TABLE FOOTER --}}

            <div
                class="
                    flex
                    flex-col
                    gap-2
                    border-t
                    border-gray-100
                    bg-gray-50/50
                    px-6
                    py-4
                    text-xs
                    text-gray-400
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >

                <span>
                    System backup history
                </span>


                <span>
                    DySign • Backup Management
                </span>

            </div>



            {{-- PAGINATION --}}

            @if(method_exists($backups, 'hasPages') && $backups->hasPages())

                <div
                    class="
                        border-t
                        border-gray-100
                        bg-white
                        px-6
                        py-4
                    "
                >
                    {{ $backups->links() }}
                </div>

            @endif

        </div>

    </section>


</div>



<script>

    function handleBackupSubmit(form) {

        const button =
            document.getElementById(
                'create-backup-button'
            );

        const text =
            document.getElementById(
                'create-backup-text'
            );


        if (!button || !text) {
            return true;
        }


        button.disabled = true;

        text.textContent =
            'Creating Backup...';


        return true;
    }

</script>


</x-admin-layout>
