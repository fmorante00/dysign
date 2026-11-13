<aside class="fixed left-0 top-0 h-screen w-72 bg-[#101064] text-white shadow-xl">

    <div class="flex h-full flex-col">


        <!-- ================================================= -->
        <!-- LOGO -->
        <!-- ================================================= -->

        <div class="border-b border-white/10 px-6 py-5">

            <div class="flex items-center gap-3">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="h-12 w-12 object-contain"
                    alt="DySign Logo"
                >

                <div>

                    <h1 class="text-2xl font-bold">
                        Dy<span class="text-[#D4A017]">Sign</span>
                    </h1>

                    <p class="text-xs text-gray-300">
                        Digital Identity System
                    </p>

                </div>

            </div>

        </div>



        <!-- ================================================= -->
        <!-- NAVIGATION -->
        <!-- ================================================= -->

        <nav
            x-data="{
                user: {{
                    request()->routeIs('personnel.*')
                    || request()->routeIs('roles.*')
                        ? 'true'
                        : 'false'
                }},

                student: {{
                    request()->routeIs('students.*')
                    || request()->routeIs('rfid-cards.*')
                        ? 'true'
                        : 'false'
                }},

                event: {{
                    request()->routeIs('events.*')
                    || request()->routeIs('announcements.*')
                    || request()->routeIs('staff.excuse-requests.*')
                    || request()->routeIs('staff.evaluation-forms.*')
                    || request()->routeIs('staff.evaluation-analysis.*')
                        ? 'true'
                        : 'false'
                }},

                attendance: {{
                    request()->routeIs('attendance.*')
                    || request()->routeIs('participation.*')
                        ? 'true'
                        : 'false'
                }},

                reports: {{
                    request()->routeIs('reports.*')
                        ? 'true'
                        : 'false'
                }},

                system: {{
                    request()->routeIs('logs.*')
                    || request()->routeIs('backup.*')
                        ? 'true'
                        : 'false'
                }}
            }"
            class="flex-1 overflow-y-auto px-4 py-5"
        >

            @php

                $currentUser = auth()->user();
                $role = $currentUser?->role?->role_name;

                // Optional future permission-aware student/attendance dual access.
                // Do not use these links until matching backend middleware is implemented.
                $showStudentNavigation = $role === 'Student Account'
                    || (method_exists($currentUser, 'canAccessStudentPortal') && $currentUser->canAccessStudentPortal());
                $showAttendanceNavigation = $role === 'Attendance Personnel'
                    || (method_exists($currentUser, 'canAccessAttendancePortal') && $currentUser->canAccessAttendancePortal());

                $link =
                    'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition hover:bg-white/10';

                $child =
                    'ml-5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-white/10';

                $active =
                    'bg-[#D4A017]/20 text-[#D4A017]';

                // This is the permanent sidebar route contract for all four roles.
                // Routes that do not exist yet are displayed as disabled "Soon" items.
                // When the named GET route is implemented, the item automatically becomes clickable.
                $sidebarExtras = [
                    'admin_user' => [
                        ['label' => 'Roles & Permissions', 'route' => 'roles.index', 'active' => 'roles.*', 'permission' => 'manage_personnel_accounts'], // #1
                    ],

                    'admin_student' => [
                        ['label' => 'RFID Card Lifecycle', 'route' => 'rfid-cards.index', 'active' => 'rfid-cards.*'], // #23
                    ],
                    'staff_events' => [
                        ['label' => 'Participant Registration', 'route' => 'events.registrations.index', 'active' => 'events.registrations.*'], // #7
                        ['label' => 'Evaluation Analysis', 'route' => 'staff.evaluation-analysis.index', 'active' => 'staff.evaluation-analysis.*'], // #18
                    ],
                    'staff_attendance' => [
                        ['label' => 'Attendance Exceptions', 'route' => 'staff.attendance-exceptions.index', 'active' => 'staff.attendance-exceptions.*'], // #10
                        ['label' => 'Discrepancy Reviews', 'route' => 'staff.attendance-discrepancies.index', 'active' => 'staff.attendance-discrepancies.*'], // #22
                    ],
                    'staff_participation' => [
                        ['label' => 'Participation Progress', 'route' => 'staff.participation-progress.index', 'active' => 'staff.participation-progress.*'], // #14
                        ['label' => 'Participation Classification', 'route' => 'staff.participation-classifications.index', 'active' => 'staff.participation-classifications.*'], // #15
                        ['label' => 'Attendance & Participation Reports', 'route' => 'staff.reports.index', 'active' => 'staff.reports.*'], // #19
                    ],
                    'personnel_attendance' => [
                        ['label' => 'Attendance Exceptions', 'route' => 'personnel.attendance-exceptions.index', 'active' => 'personnel.attendance-exceptions.*'], // #10
                        ['label' => 'Discrepancy Verification', 'route' => 'personnel.attendance-discrepancies.index', 'active' => 'personnel.attendance-discrepancies.*'], // #22
                    ],
                    'student_registration' => [
                        ['label' => 'My Event Registrations', 'route' => 'student.registrations.index', 'active' => 'student.registrations.*'], // #7
                    ],
                    'student_progress' => [
                        ['label' => 'My Attendance History', 'route' => 'student.attendance-history.index', 'active' => 'student.attendance-history.*'], // #9
                        ['label' => 'My Participation Progress', 'route' => 'student.participation-progress.index', 'active' => 'student.participation-progress.*'], // #14
                        ['label' => 'Attendance Discrepancies', 'route' => 'student.attendance-discrepancies.index', 'active' => 'student.attendance-discrepancies.*'], // #22
                    ],
                ];

            @endphp



            <!-- ================================================= -->
            <!-- ADMINISTRATOR -->
            <!-- ================================================= -->

            @if($role === 'Administrator')


                <!-- DASHBOARD -->

                <a
                    href="/dashboard"
                    class="
                        {{ $link }}
                        {{
                            request()->is('dashboard')
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 12l9-9 9 9M5 10v10h14V10"
                        />
                    </svg>

                    <span>
                        Dashboard
                    </span>

                </a>



                <!-- ================================================= -->
                <!-- USER MANAGEMENT -->
                <!-- ================================================= -->

                <button
                    @click="user = !user"
                    class="{{ $link }} mt-4 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1m4-4a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        User Management
                    </span>

                    <span x-text="user ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="user"
                    x-collapse
                    class="mt-1"
                >

                    @if(
                        auth()->user()
                            ->hasPermission(
                                'manage_personnel_accounts'
                            )
                    )

                        <a
                            href="{{ route('personnel.index') }}"
                            class="
                                {{ $child }}
                                {{
                                    request()->routeIs('personnel.*')
                                        ? $active
                                        : ''
                                }}
                            "
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Staff Accounts

                        </a>

                    @endif




                    <!-- BEGIN planned module links: admin_user -->
                    @foreach ($sidebarExtras['admin_user'] as $item)
                        @if (!isset($item['permission']) || auth()->user()->hasPermission($item['permission']))
                            @php
                                $ready = \Illuminate\Support\Facades\Route::has($item['route']);
                                $isActive = $ready && request()->routeIs($item['active']);
                                $extraClass = $child;
                            @endphp
                            @if ($ready)
                                <a href="{{ route($item['route']) }}"
                                   class="{{ $extraClass }} {{ $isActive ? $active : '' }}">
                                    <span class="text-[#D4A017]" aria-hidden="true">•</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <div class="{{ $extraClass }} cursor-not-allowed text-white/50 hover:bg-transparent"
                                     title="This DySign module is not installed yet" aria-disabled="true">
                                    <span class="text-[#D4A017]/60" aria-hidden="true">•</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium">Soon</span>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <!-- END planned module links: admin_user -->

                </div>



                <!-- ================================================= -->
                <!-- STUDENT MANAGEMENT -->
                <!-- ================================================= -->

                <button
                    @click="student = !student"
                    class="{{ $link }} mt-3 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.42M12 14v7"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        Student Management
                    </span>

                    <span x-text="student ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="student"
                    x-collapse
                    class="mt-1"
                >

                    @if(
                        auth()->user()
                            ->hasPermission(
                                'import_student_data'
                            )
                    )

                        <a
                            href="{{ route('students.index') }}"
                            class="{{ $child }} {{ request()->routeIs('students.*') && !request()->routeIs('students.accounts.*') ? $active : '' }}"
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Student Data

                        </a>

                    @endif


                    <a
                        href="{{ route('students.accounts.index') }}"
                        class="{{ $child }} {{ request()->routeIs('students.accounts.*') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Account Provisioning

                    </a>

                    <!-- BEGIN planned module links: admin_student -->
                    @foreach ($sidebarExtras['admin_student'] as $item)
                        @if (!isset($item['permission']) || auth()->user()->hasPermission($item['permission']))
                            @php
                                $ready = \Illuminate\Support\Facades\Route::has($item['route']);
                                $isActive = $ready && request()->routeIs($item['active']);
                                $extraClass = $child;
                            @endphp
                            @if ($ready)
                                <a href="{{ route($item['route']) }}"
                                   class="{{ $extraClass }} {{ $isActive ? $active : '' }}">
                                    <span class="text-[#D4A017]" aria-hidden="true">•</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <div class="{{ $extraClass }} cursor-not-allowed text-white/50 hover:bg-transparent"
                                     title="This DySign module is not installed yet" aria-disabled="true">
                                    <span class="text-[#D4A017]/60" aria-hidden="true">•</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium">Soon</span>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <!-- END planned module links: admin_student -->

                </div>



                <!-- ================================================= -->
                <!-- EVENT MANAGEMENT -->
                <!-- ================================================= -->

                <button
                    @click="event = !event"
                    class="{{ $link }} mt-3 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        Event Management
                    </span>

                    <span x-text="event ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="event"
                    x-collapse
                    class="mt-1"
                >

                    <a
                        href="{{ route('events.index') }}"
                        class="{{ $child }} {{ request()->routeIs('events.index', 'events.create', 'events.show', 'events.edit') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Events

                    </a>


                    <a
                        href="{{ route('events.eligibility.events') }}"
                        class="{{ $child }} {{ request()->routeIs('events.eligibility.*') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participant Eligibility

                    </a>

                </div>



                <!-- ================================================= -->
                <!-- ATTENDANCE MANAGEMENT -->
                <!-- ================================================= -->

                <button
                    @click="attendance = !attendance"
                    class="{{ $link }} mt-3 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M13 10V3L4 14h7v7l9-11h-7z"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        Attendance Management
                    </span>

                    <span
                        x-text="
                            attendance
                                ? '⌄'
                                : '›'
                        "
                    ></span>

                </button>


                <div
                    x-show="attendance"
                    x-collapse
                    class="mt-1"
                >

                    @if(
                        auth()->user()
                            ->hasPermission(
                                'view_attendance'
                            )
                    )

                        <a
                            href="{{ route('attendance.monitor') }}"
                            class="
                                {{ $child }}
                                {{
                                    request()->routeIs(
                                        'attendance.monitor'
                                    )
                                        ? $active
                                        : ''
                                }}
                            "
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Attendance Monitoring

                        </a>


                    @endif


                    <a
                        href="{{ route('participation.records') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'participation.records'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participation Records

                    </a>


                    <a
                        href="{{ route('participation.evaluation') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'participation.evaluation'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participation Evaluation

                    </a>

                </div>



                <!-- ================================================= -->
                <!-- REPORTS -->
                <!-- ================================================= -->

                <button
                    @click="reports = !reports"
                    class="{{ $link }} mt-3 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3v18h18M7 16v-5m5 5V8m5 8V5"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        Reports & Analytics
                    </span>

                    <span x-text="reports ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="reports"
                    x-collapse
                    class="mt-1"
                >

                    <a
                        href="{{ route('reports.attendance') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'reports.attendance'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Attendance Reports

                    </a>


                    <a
                        href="{{ route('reports.participation') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'reports.participation'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participation Reports

                    </a>

                </div>



                <!-- ================================================= -->
                <!-- SYSTEM MANAGEMENT -->
                <!-- ================================================= -->

                <button
                    @click="system = !system"
                    class="{{ $link }} mt-3 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        System Management
                    </span>

                    <span x-text="system ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="system"
                    x-collapse
                    class="mt-1"
                >

                    <a
                        href="{{ route('logs.index') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs('logs.*')
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Activity Logs

                    </a>


                    <a
                        href="{{ route('backup.index') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs('backup.*')
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Backup & Recovery

                    </a>

                </div>


            @endif



            <!-- ================================================= -->
            <!-- DEPARTMENT STAFF -->
            <!-- ================================================= -->

            @if($role === 'Department Staff')


                <!-- DASHBOARD -->

                <a
                    href="/dashboard"
                    class="
                        {{ $link }}
                        {{
                            request()->is('dashboard')
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 12l9-9 9 9M5 10v10h14V10"
                        />
                    </svg>

                    <span>
                        Dashboard
                    </span>

                </a>



                <!-- ================================================= -->
                <!-- EVENT MANAGEMENT -->
                <!-- ================================================= -->

                <button
                    @click="event = !event"
                    class="{{ $link }} mt-4 w-full"
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                        />
                    </svg>

                    <span class="flex-1 text-left">
                        Event Management
                    </span>

                    <span x-text="event ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="event"
                    x-collapse
                    class="mt-1"
                >

                    {{-- EVENTS --}}

                    <a
                        href="{{ route('events.index') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'events.index',
                                    'events.create',
                                    'events.show',
                                    'events.edit'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Events

                    </a>



                    {{-- PARTICIPANT ELIGIBILITY --}}

                    <a
                        href="{{ route('events.eligibility.events') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'events.eligibility.*'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participant Eligibility

                    </a>



                    {{-- PERSONNEL ASSIGNMENT --}}

                    <a
                        href="{{ route('events.personnel-assignment.events') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'events.personnel-assignment.*'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Personnel Assignment

                    </a>



                    {{-- ANNOUNCEMENTS --}}

                    <a
                        href="{{ route('announcements.index') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'announcements.*'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Announcements

                    </a>



                    {{-- EXCUSE REQUESTS --}}

                    <a
                        href="{{ route('staff.excuse-requests.index') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'staff.excuse-requests.*'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Excuse Requests

                    </a>



                    {{-- EVALUATION FORMS --}}

                    <a
                        href="{{ route('staff.evaluation-forms.index') }}"
                        class="
                            {{ $child }}
                            {{
                                request()->routeIs(
                                    'staff.evaluation-forms.*'
                                )
                                    ? $active
                                    : ''
                            }}
                        "
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Evaluation Forms

                    </a>


                    <!-- BEGIN planned module links: staff_events -->
                    @foreach ($sidebarExtras['staff_events'] as $item)
                        @if (!isset($item['permission']) || auth()->user()->hasPermission($item['permission']))
                            @php
                                $ready = \Illuminate\Support\Facades\Route::has($item['route']);
                                $isActive = $ready && request()->routeIs($item['active']);
                                $extraClass = $child;
                            @endphp
                            @if ($ready)
                                <a href="{{ route($item['route']) }}"
                                   class="{{ $extraClass }} {{ $isActive ? $active : '' }}">
                                    <span class="text-[#D4A017]" aria-hidden="true">•</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <div class="{{ $extraClass }} cursor-not-allowed text-white/50 hover:bg-transparent"
                                     title="This DySign module is not installed yet" aria-disabled="true">
                                    <span class="text-[#D4A017]/60" aria-hidden="true">•</span>
                                    <span class="flex-1">{{ $item['label'] }}</span>
                                    <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium">Soon</span>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <!-- END planned module links: staff_events -->

                </div>



                <!-- ================================================= -->
                <!-- ACTIVITY POINT MANAGEMENT - MODULE #13 -->
                <!-- ================================================= -->

                <a
                    href="{{ route('staff.activity-points.index') }}"
                    class="
                        {{ $link }}
                        mt-3
                        {{
                            request()->routeIs('staff.activity-points.*')
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 21h8m-4-4v4M7 4h10v6a5 5 0 0 1-10 0V4zm10 1h4v3a4 4 0 0 1-4 4M7 5H3v3a4 4 0 0 0 4 4"
                        />
                    </svg>

                    <span>
                        Activity Point Management
                    </span>

                </a>


                <!-- BEGIN planned module links: staff_attendance -->
                @foreach ($sidebarExtras['staff_attendance'] as $item)
                    @if (!isset($item['permission']) || auth()->user()->hasPermission($item['permission']))
                        @php
                            $ready = \Illuminate\Support\Facades\Route::has($item['route']);
                            $isActive = $ready && request()->routeIs($item['active']);
                            $extraClass = $link . ' mt-3';
                        @endphp
                        @if ($ready)
                            <a href="{{ route($item['route']) }}"
                               class="{{ $extraClass }} {{ $isActive ? $active : '' }}">
                                <svg class="h-5 w-5 shrink-0 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                                <span class="flex-1">{{ $item['label'] }}</span>
                            </a>
                        @else
                            <div class="{{ $extraClass }} cursor-not-allowed text-white/50 hover:bg-transparent"
                                 title="This DySign module is not installed yet" aria-disabled="true">
                                <svg class="h-5 w-5 shrink-0 text-[#D4A017]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                                <span class="flex-1">{{ $item['label'] }}</span>
                                <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium">Soon</span>
                            </div>
                        @endif
                    @endif
                @endforeach
                <!-- END planned module links: staff_attendance -->

                <!-- BEGIN planned module links: staff_participation -->
                @foreach ($sidebarExtras['staff_participation'] as $item)
                    @if (!isset($item['permission']) || auth()->user()->hasPermission($item['permission']))
                        @php
                            $ready = \Illuminate\Support\Facades\Route::has($item['route']);
                            $isActive = $ready && request()->routeIs($item['active']);
                            $extraClass = $link . ' mt-3';
                        @endphp
                        @if ($ready)
                            <a href="{{ route($item['route']) }}"
                               class="{{ $extraClass }} {{ $isActive ? $active : '' }}">
                                <svg class="h-5 w-5 shrink-0 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                                <span class="flex-1">{{ $item['label'] }}</span>
                            </a>
                        @else
                            <div class="{{ $extraClass }} cursor-not-allowed text-white/50 hover:bg-transparent"
                                 title="This DySign module is not installed yet" aria-disabled="true">
                                <svg class="h-5 w-5 shrink-0 text-[#D4A017]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                                <span class="flex-1">{{ $item['label'] }}</span>
                                <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium">Soon</span>
                            </div>
                        @endif
                    @endif
                @endforeach
                <!-- END planned module links: staff_participation -->

                <!-- ================================================= -->
                <!-- PERSONNEL AVAILABILITY -->
                <!-- ================================================= -->

                <a
                    href="{{ route('personnel-availability.staff') }}"
                    class="
                        {{ $link }}
                        mt-3
                        {{
                            request()->routeIs(
                                'personnel-availability.staff'
                            )
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="
                                M8 7V3
                                m8 4V3
                                M5 11h14
                                M5 7h14v14H5z
                                M9 15l2 2 4-4
                            "
                        />
                    </svg>

                    <span>
                        Personnel Availability
                    </span>

                </a>



                <!-- ================================================= -->
                <!-- PERSONNEL DUTY MONITORING -->
                <!-- ================================================= -->

                <a
                    href="{{ route('personnel-duties.staff') }}"
                    class="
                        {{ $link }}
                        mt-3
                        {{
                            request()->routeIs(
                                'personnel-duties.staff'
                            )
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5-4.5A11.95 11.95 0 0112 3a11.95 11.95 0 01-8 2.5V11c0 5.25 3.4 9.74 8 11 4.6-1.26 8-5.75 8-11V5.5z"
                        />
                    </svg>

                    <span>
                        Duty Monitoring
                    </span>

                </a>

            @endif



            <!-- ================================================= -->
            <!-- ATTENDANCE PERSONNEL -->
            <!-- ================================================= -->

            @if($showAttendanceNavigation)


                <!-- DASHBOARD -->

                <a
                    href="/dashboard"
                    class="
                        {{ $link }}
                        {{
                            request()->is('dashboard')
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 12l9-9 9 9M5 10v10h14V10"
                        />
                    </svg>

                    <span>
                        Dashboard
                    </span>

                </a>



                <!-- ================================================= -->
                <!-- MY ASSIGNED EVENTS -->
                <!-- ================================================= -->

                <a
                    href="{{ route('my-events.index') }}"
                    class="
                        {{ $link }}
                        mt-3
                        {{
                            request()->routeIs(
                                'my-events.*'
                            )
                            ||
                            request()->routeIs(
                                'attendance.index'
                            )
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"
                        />
                    </svg>

                    <span>
                        My Assigned Events / RFID Scanning
                    </span>

                </a>



                <!-- BEGIN planned module links: personnel_attendance -->
                @foreach ($sidebarExtras['personnel_attendance'] as $item)
                    @if (!isset($item['permission']) || auth()->user()->hasPermission($item['permission']))
                        @php
                            $ready = \Illuminate\Support\Facades\Route::has($item['route']);
                            $isActive = $ready && request()->routeIs($item['active']);
                            $extraClass = $link . ' mt-3';
                        @endphp
                        @if ($ready)
                            <a href="{{ route($item['route']) }}"
                               class="{{ $extraClass }} {{ $isActive ? $active : '' }}">
                                <svg class="h-5 w-5 shrink-0 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                                <span class="flex-1">{{ $item['label'] }}</span>
                            </a>
                        @else
                            <div class="{{ $extraClass }} cursor-not-allowed text-white/50 hover:bg-transparent"
                                 title="This DySign module is not installed yet" aria-disabled="true">
                                <svg class="h-5 w-5 shrink-0 text-[#D4A017]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" /></svg>
                                <span class="flex-1">{{ $item['label'] }}</span>
                                <span class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-medium">Soon</span>
                            </div>
                        @endif
                    @endif
                @endforeach
                <!-- END planned module links: personnel_attendance -->

                <!-- ================================================= -->
                <!-- MY AVAILABILITY -->
                <!-- ================================================= -->

                <a
                    href="{{ route('personnel-availability.index') }}"
                    class="
                        {{ $link }}
                        mt-3
                        {{
                            request()->routeIs(
                                'personnel-availability.index',
                                'personnel-availability.store',
                                'personnel-availability.update',
                                'personnel-availability.destroy'
                            )
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="
                                M8 7V3
                                m8 4V3
                                M5 11h14
                                M5 7h14v14H5z
                                M12 14v3
                                m0 0h.01
                            "
                        />
                    </svg>

                    <span>
                        My Availability
                    </span>

                </a>



                <!-- ================================================= -->
                <!-- MY DUTIES -->
                <!-- ================================================= -->

                <a
                    href="{{ route('personnel-duties.index') }}"
                    class="
                        {{ $link }}
                        mt-3
                        {{
                            request()->routeIs(
                                'personnel-duties.index',
                                'personnel-duties.check-in',
                                'personnel-duties.check-out'
                            )
                                ? $active
                                : ''
                        }}
                    "
                >

                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 11l3 3L22 4M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"
                        />
                    </svg>

                    <span>
                        My Duties
                    </span>

                </a>


                <!-- ================================================= -->
                <!-- MODULE 22 - ATTENDANCE DISCREPANCY REVIEW -->
                <!-- ================================================= -->

                <a
                    href="{{ route('attendance-discrepancies.staff.index') }}"
                    class="{{ $link }} mt-3 {{ request()->routeIs('attendance-discrepancies.staff.*') ? $active : '' }}"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        aria-hidden="true"
                    >
                        <path
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12h6m-6 4h6M5 3h14v18H5V3z"
                        />
                    </svg>
                    <span>Attendance Review Requests</span>
                </a>


            @endif



            <!-- ================================================= -->
            <!-- STUDENT ACCOUNT - ORGANIZED NAVIGATION -->
            <!-- ================================================= -->
            @if($showStudentNavigation)
                @php
                    // Student-only presentation data. Backend routes/permissions stay unchanged.
                    // Keep both discrepancy workflows visible until they are consolidated in the backend.
                    $studentNavGroups = [
                        [
                            'heading' => null,
                            'items' => [
                                [
                                    'label' => 'Dashboard',
                                    'route' => 'dashboard',
                                    'active' => 'dashboard',
                                    'icon' => 'M3 12l9-9 9 9M5 10v10h14V10',
                                ],
                            ],
                        ],
                        [
                            'heading' => 'EVENTS & ACTIVITIES',
                            'items' => [
                                [
                                    'label' => 'My Event Registrations',
                                    'route' => 'student.registrations.index',
                                    'active' => 'student.registrations.*',
                                    'icon' => 'M8 3v4m8-4v4M4 10h16M5 5h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2zm4 10 2 2 4-4',
                                ],
                                [
                                    'label' => 'My Evaluations',
                                    'route' => 'student.evaluations.index',
                                    'active' => 'student.evaluations.*',
                                    'icon' => 'M9 3h6l1 2h3v16H5V5h3l1-2zm-1 11 3 3 5-6',
                                ],
                            ],
                        ],
                        [
                            'heading' => 'ATTENDANCE & CONCERNS',
                            'items' => [
                                [
                                    'label' => 'My Attendance History',
                                    'route' => 'student.attendance-history.index',
                                    'active' => 'student.attendance-history.*',
                                    'icon' => 'M3 5h18v16H3zM7 3v4m10-4v4M3 10h18m-13 5 2 2 4-4',
                                ],
                                [
                                    'label' => 'Attendance Discrepancies',
                                    'route' => 'student.attendance-discrepancies.index',
                                    'active' => 'student.attendance-discrepancies.*',
                                    'icon' => 'M12 9v4m0 4h.01M10.3 3.9 2 18h19l-8-18a2 2 0 0 0-3.7 0z',
                                ],
                                [
                                    'label' => 'Attendance Review Requests',
                                    'route' => 'attendance-discrepancies.student.index',
                                    'active' => 'attendance-discrepancies.student.*',
                                    'icon' => 'M6 3h9l4 4v14H6zM14 3v5h5M9 12h7m-7 4h5',
                                ],
                                [
                                    'label' => 'My Excuse Requests',
                                    'route' => 'student.excuse-requests.index',
                                    'active' => 'student.excuse-requests.*',
                                    'icon' => 'M6 3h9l4 4v14H6zM14 3v5h5M9 12h6m-6 4h6',
                                ],
                            ],
                        ],
                        [
                            'heading' => 'INFORMATION & PROGRESS',
                            'items' => [
                                [
                                    'label' => 'My Announcements',
                                    'route' => 'student.announcements.index',
                                    'active' => 'student.announcements.*',
                                    'icon' => 'M3 10v4h4l10 5V5L7 10H3zm4 4 2 6',
                                ],
                                [
                                    'label' => 'My Participation Progress',
                                    'route' => 'student.participation-progress.index',
                                    'active' => 'student.participation-progress.*',
                                    'icon' => 'M4 20V4M4 20h16M8 16v-4m4 4V8m4 8V5',
                                ],
                            ],
                        ],
                    ];
                @endphp

                @foreach($studentNavGroups as $group)
                    @if($group['heading'])
                        <div class="mt-6 mb-2 px-3 text-[10px] font-bold uppercase tracking-[0.14em] text-white/55">
                            {{ $group['heading'] }}
                        </div>
                    @endif

                    <div class="space-y-1">
                        @foreach($group['items'] as $item)
                            @php
                                $studentRouteReady = \Illuminate\Support\Facades\Route::has($item['route']);
                                $studentIsActive = $studentRouteReady && request()->routeIs($item['active']);
                                $studentLinkClass = $link . ($studentIsActive ? ' ' . $active : '');
                            @endphp
                            @if($studentRouteReady)
                                <a href="{{ route($item['route']) }}"
                                   class="{{ $studentLinkClass }}"
                                   @if($studentIsActive) aria-current="page" @endif>
                                    <svg class="h-5 w-5 shrink-0 {{ $studentIsActive ? 'text-[#D4A017]' : 'text-white/75' }}"
                                         fill="none" stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="min-w-0 flex-1 leading-snug">{{ $item['label'] }}</span>
                                </a>
                            @else
                                <div class="{{ $link }} cursor-not-allowed text-white/45 hover:bg-transparent"
                                     aria-disabled="true" title="This DySign module is not installed yet">
                                    <svg class="h-5 w-5 shrink-0 text-white/35" fill="none" stroke="currentColor"
                                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                         viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span class="min-w-0 flex-1 leading-snug">{{ $item['label'] }}</span>
                                    <span class="rounded bg-white/10 px-1.5 py-0.5 text-[10px]">Soon</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            @endif


        </nav>

    </div>

</aside>