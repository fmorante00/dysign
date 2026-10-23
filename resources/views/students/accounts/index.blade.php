<x-admin-layout>

    <div class="space-y-8">

        {{-- PAGE HEADER --}}
        <section class="overflow-hidden rounded-3xl bg-[#101064] text-white">

            <div class="p-8 md:p-10">

                <p class="text-xs font-bold uppercase tracking-[0.3em] text-[#D4A017]">
                    Student Account Management
                </p>

                <h1 class="mt-3 text-3xl font-bold">
                    Automated Account Provisioning
                </h1>

                <p class="mt-3 max-w-2xl text-sm leading-6 text-white/70">
                    Automatically evaluate imported student records,
                    identify eligible accounts, and securely prepare
                    student login accounts for bulk provisioning.
                </p>

                <div class="mt-6">
                    <a href="{{ route('students.index') }}"
                       class="inline-flex items-center rounded-lg border border-white/30 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">
                        ← Back to Student Records
                    </a>
                </div>

            </div>

            <div class="h-1 bg-[#D4A017]"></div>

        </section>


        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))

            <div class="rounded-xl border border-green-200 bg-green-50 px-6 py-4">
                <p class="font-semibold text-green-800">
                    Action Completed
                </p>

                <p class="mt-1 text-sm text-green-700">
                    {{ session('success') }}
                </p>
            </div>

        @endif


        {{-- INFORMATION MESSAGE --}}
        @if(session('info'))

            <div class="rounded-xl border border-blue-200 bg-blue-50 px-6 py-4">
                <p class="text-sm font-medium text-blue-800">
                    {{ session('info') }}
                </p>
            </div>

        @endif


        {{-- VALIDATION ERRORS --}}
        @if($errors->any())

            <div class="rounded-xl border border-red-200 bg-red-50 px-6 py-4">

                <p class="font-semibold text-red-800">
                    Unable to Process Request
                </p>

                @foreach($errors->all() as $error)
                    <p class="mt-1 text-sm text-red-700">
                        {{ $error }}
                    </p>
                @endforeach

            </div>

        @endif


        {{-- ACCOUNT SUMMARY --}}
        <section>

            <div class="mb-5">

                <p class="text-xs font-bold uppercase tracking-widest text-[#D4A017]">
                    Overview
                </p>

                <h2 class="mt-2 text-xl font-bold text-[#101064]">
                    Account Provisioning Summary
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Automatically calculated from imported student records.
                </p>

            </div>

            @php
                $needsVerification =
                    $summary['missing_email']
                    + $summary['invalid_email']
                    + $summary['duplicate_email']
                    + $summary['email_in_use'];
            @endphp

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

                {{-- TOTAL STUDENTS --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Total Students
                    </p>

                    <p class="mt-3 text-4xl font-bold text-[#101064]">
                        {{ number_format($summary['total_students']) }}
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Imported student records
                    </p>

                </div>


                {{-- ELIGIBLE STUDENTS --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Eligible for Provisioning
                    </p>

                    <p class="mt-3 text-4xl font-bold text-green-600">
                        {{ number_format($summary['eligible']) }}
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Ready for account creation
                    </p>

                </div>


                {{-- EXISTING ACCOUNTS --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Existing Accounts
                    </p>

                    <p class="mt-3 text-4xl font-bold text-blue-600">
                        {{ number_format($summary['already_provisioned']) }}
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Students with linked accounts
                    </p>

                </div>


                {{-- VERIFICATION REQUIRED --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Needs Verification
                    </p>

                    <p class="mt-3 text-4xl font-bold text-red-600">
                        {{ number_format($needsVerification) }}
                    </p>

                    <p class="mt-2 text-xs text-gray-400">
                        Records requiring email verification
                    </p>

                </div>

            </div>

        </section>


        {{-- AUTOMATIC VALIDATION RESULTS --}}
        <section class="rounded-2xl border border-gray-200 bg-white p-6">

            <h2 class="text-lg font-bold text-[#101064]">
                Automatic Validation Results
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                DySign automatically identifies records that
                cannot be provisioned.
            </p>

            @php
                $validationItems = [
                    ['Missing Email', 'missing_email'],
                    ['Invalid Email', 'invalid_email'],
                    ['Duplicate Email', 'duplicate_email'],
                    ['Email Already Used', 'email_in_use'],
                    ['Inactive Students', 'inactive'],
                ];
            @endphp

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

                @foreach($validationItems as $item)

                    <div class="rounded-xl border border-gray-100 bg-gray-50 p-4">

                        <p class="text-xs font-semibold text-gray-500">
                            {{ $item[0] }}
                        </p>

                        <p class="mt-2 text-2xl font-bold text-[#101064]">
                            {{ number_format($summary[$item[1]]) }}
                        </p>

                    </div>

                @endforeach

            </div>

        </section>


        {{-- BULK ACCOUNT PROVISIONING --}}
        <section class="overflow-hidden rounded-2xl border border-[#D4A017]/40 bg-white">

            <div class="border-b border-gray-100 bg-[#FFF9E7] px-6 py-5">

                <p class="text-xs font-bold uppercase tracking-widest text-[#B68A0D]">
                    Business Process Automation
                </p>

                <h2 class="mt-2 text-xl font-bold text-[#101064]">
                    Automated Bulk Account Provisioning
                </h2>

                <p class="mt-2 max-w-3xl text-sm leading-6 text-gray-600">
                    Automatically create student login accounts using
                    validated student records. Existing accounts and
                    records requiring verification will be skipped.
                </p>

            </div>


            <div class="space-y-6 p-6">

                {{-- ELIGIBLE COUNT --}}
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-sm font-semibold text-gray-600">
                            Students Ready for Provisioning
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ number_format($summary['eligible']) }}
                        </p>

                        <p class="mt-1 text-xs text-gray-400">
                            Based on the current student registry
                        </p>

                    </div>

                    <div class="rounded-xl bg-[#F0F1F8] px-5 py-4">

                        <p class="text-xs font-semibold text-[#101064]">
                            Automated Processing
                        </p>

                        <p class="mt-1 text-xs text-gray-500">
                            Database queue · Batches of 50 students
                        </p>

                    </div>

                </div>


                {{-- PROVISIONING CONFIRMATION FORM --}}
                @if($summary['eligible'] > 0)

                    <form
                        method="POST"
                        action="{{ route('students.accounts.provision') }}"
                        onsubmit="return confirm('Are you sure you want to queue account provisioning for all currently eligible students?');"
                        class="space-y-5"
                    >

                        @csrf

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                            <label class="flex cursor-pointer items-start gap-3">

                                <input
                                    type="checkbox"
                                    name="confirm_provisioning"
                                    value="1"
                                    required
                                    class="mt-1 rounded border-gray-300 text-[#101064] focus:ring-[#101064]"
                                >

                                <span class="text-sm leading-6 text-gray-600">

                                    I confirm that I want DySign to
                                    automatically create login accounts
                                    for eligible students and queue their
                                    account setup invitations.

                                </span>

                            </label>

                        </div>


                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <p class="max-w-xl text-xs leading-5 text-gray-500">
                                The system will revalidate student records
                                before processing. Submitting this request
                                queues the work; it does not guarantee that
                                every account or invitation succeeds.
                            </p>

                            <button
                                type="submit"
                                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-[#101064] px-6 py-3 text-sm font-bold text-white transition hover:bg-[#D4A017] hover:text-[#101064]"
                            >

                                Bulk Provision Accounts →

                            </button>

                        </div>

                    </form>

                @else

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                        <p class="font-semibold text-gray-700">
                            No Eligible Students Available
                        </p>

                        <p class="mt-2 text-sm text-gray-500">
                            All student records either have existing accounts
                            or require verification before provisioning.
                        </p>

                        <button
                            type="button"
                            disabled
                            class="mt-4 cursor-not-allowed rounded-xl bg-gray-200 px-6 py-3 text-sm font-semibold text-gray-400"
                        >
                            Bulk Provision Accounts
                        </button>

                    </div>

                @endif

            </div>

        </section>


        {{-- STUDENT ELIGIBILITY TABLE --}}
        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">

            <div class="border-b border-gray-100 px-6 py-5">

                <h2 class="text-lg font-bold text-[#101064]">
                    Student Account Eligibility
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Review each student's account provisioning status.
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[1200px] text-left">

                    <thead class="bg-gray-50">

                        <tr class="text-xs uppercase tracking-wider text-gray-400">

                            <th class="px-6 py-4">Student Number</th>
                            <th class="px-6 py-4">Student Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Program</th>
                            <th class="px-6 py-4">Provisioning Status</th>
                            <th class="px-6 py-4">Account Access</th>
                            <th class="px-6 py-4">Actions</th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @forelse($records as $record)

                            <tr class="transition hover:bg-gray-50">

                                <td class="px-6 py-4 text-sm font-semibold text-gray-700">
                                    {{ $record['student_number'] }}
                                </td>

                                <td class="px-6 py-4 text-sm font-semibold text-[#101064]">
                                    {{ $record['name'] }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $record['email'] ?: 'No email' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $record['program_code'] }}
                                </td>

                                <td class="px-6 py-4">

                                    @switch($record['status'])

                                        @case('eligible')
                                            <span class="rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">
                                                Eligible
                                            </span>
                                            @break

                                        @case('already_provisioned')
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                                Account Exists
                                            </span>
                                            @break

                                        @case('inactive')
                                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                                                Inactive
                                            </span>
                                            @break

                                        @case('missing_email')
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Missing Email
                                            </span>
                                            @break

                                        @case('invalid_email')
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Invalid Email
                                            </span>
                                            @break

                                        @case('duplicate_email')
                                            <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-semibold text-orange-700">
                                                Duplicate Email
                                            </span>
                                            @break

                                        @case('email_in_use')
                                            <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">
                                                Email Already Used
                                            </span>
                                            @break

                                    @endswitch

                                </td>

                                {{-- Student login account state (separate from provisioning eligibility) --}}
                                <td class="px-6 py-4">
                                    @if(!$record['account_user_id'])
                                        <span class="text-xs text-gray-400">Not provisioned</span>
                                    @elseif($record['account_deactivated_at'] !== null)
                                        <span class="inline-flex rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Deactivated</span>
                                    @elseif($record['account_status'] === 'Active')
                                        <span class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-semibold text-green-700">Active</span>
                                    @elseif($record['account_setup_pending'])
                                        <span class="inline-flex rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Pending Setup</span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">Inactive</span>
                                    @endif
                                </td>

                                {{-- Account-management actions: only for linked Student Account users --}}
                                <td class="px-6 py-4">
                                    @if(!$record['account_user_id'] || !$record['account_is_student_role'])
                                        <span class="text-xs text-gray-400">—</span>
                                    @elseif($record['account_deactivated_at'] !== null || ($record['account_status'] === 'Inactive' && !$record['account_setup_pending']))
                                        @if($record['student_record_active'])
                                            <form method="POST"
                                                  action="{{ route('students.accounts.activate', ['id' => $record['account_user_id']]) }}"
                                                  onsubmit="return confirm('Reactivate this student account? If account setup is pending, the student must still complete setup.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="rounded-lg border border-green-600 px-4 py-2 text-xs font-bold text-green-700 transition hover:bg-green-50">
                                                    Reactivate
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">Student record inactive</span>
                                        @endif
                                    @else
                                        <form method="POST"
                                              action="{{ route('students.accounts.deactivate', ['id' => $record['account_user_id']]) }}"
                                              onsubmit="return confirm('{{ $record['account_setup_pending'] ? 'Disable this student account before setup? The student cannot complete invitation setup while disabled.' : 'Deactivate this student account? The student will be blocked from accessing DySign.' }}');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 px-4 py-2 text-xs font-bold text-red-700 transition hover:bg-red-50">
                                                {{ $record['account_setup_pending'] ? 'Disable Account' : 'Deactivate' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="px-6 py-16 text-center">

                                    <p class="font-semibold text-[#101064]">
                                        No student records found
                                    </p>

                                    <p class="mt-2 text-sm text-gray-400">
                                        Import student records to begin
                                        account eligibility checks.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if($records->isNotEmpty())

                <div class="border-t border-gray-100 px-6 py-4 text-xs text-gray-400">

                    Showing {{ number_format($records->count()) }}
                    evaluated student records.

                </div>

            @endif

        </section>

    </div>

</x-admin-layout>
