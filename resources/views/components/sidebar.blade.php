<aside class="fixed left-0 top-0 h-screen w-72 bg-[#101064] text-white shadow-xl">

    <div class="flex h-full flex-col">


        <!-- LOGO -->

        <div class="border-b border-white/10 px-6 py-5">

            <div class="flex items-center gap-3">

                <img
                    src="{{ asset('images/logo.png') }}"
                    class="h-12 w-12 object-contain"
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



        <!-- NAVIGATION -->

        <nav
            x-data="{
                user: {{ request()->routeIs('personnel.*') || request()->routeIs('roles.*') ? 'true' : 'false' }},
                student: {{ request()->routeIs('students.*') ? 'true' : 'false' }},
                event: {{ request()->routeIs('events.*') || request()->routeIs('announcements.*') ? 'true' : 'false' }},
                attendance: {{ request()->routeIs('attendance.*') || request()->routeIs('participation.*') ? 'true' : 'false' }},
                reports: {{ request()->routeIs('reports.*') ? 'true' : 'false' }},
                system: {{ request()->routeIs('logs.*') || request()->routeIs('backup.*') ? 'true' : 'false' }}
            }"
            class="flex-1 overflow-y-auto px-4 py-5"
        >

            @php

                $role = auth()->user()->role->role_name;

                $link = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition hover:bg-white/10';

                $child = 'ml-5 flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-white/10';

                $active = 'bg-[#D4A017]/20 text-[#D4A017]';

            @endphp





            <!-- ================================================= -->
            <!-- ADMINISTRATOR -->
            <!-- ================================================= -->

            @if($role === 'Administrator')


                <!-- DASHBOARD -->

                <a
                    href="/dashboard"
                    class="{{ $link }} {{ request()->is('dashboard') ? $active : '' }}"
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





                <!-- USER MANAGEMENT -->

                <button
                    @click="user=!user"
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

                    @if(auth()->user()->hasPermission('manage_personnel_accounts'))

                        <a
                            href="{{ route('personnel.index') }}"
                            class="{{ $child }} {{ request()->routeIs('personnel.*') ? $active : '' }}"
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Personnel Management

                        </a>

                    @endif



                    @if(auth()->user()->hasPermission('assign_roles'))

                        <a
                            href="{{ route('roles.index') }}"
                            class="{{ $child }} {{ request()->routeIs('roles.*') ? $active : '' }}"
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Roles & Access

                        </a>

                    @endif

                </div>





                <!-- STUDENT RECORDS -->

                <button
                    @click="student=!student"
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
                        Student Records
                    </span>

                    <span x-text="student ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="student"
                    x-collapse
                    class="mt-1"
                >

                    @if(auth()->user()->hasPermission('import_student_data'))

                        <a
                            href="{{ route('students.index') }}"
                            class="{{ $child }} {{ request()->routeIs('students.*') ? $active : '' }}"
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Student Data

                        </a>

                    @endif

                </div>





                <!-- EVENT MANAGEMENT -->

                <button
                    @click="event=!event"
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
                        class="{{ $child }} {{ request()->routeIs('events.*') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Events

                    </a>


                    <a
                        href="{{ route('announcements.index') }}"
                        class="{{ $child }} {{ request()->routeIs('announcements.*') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Announcements

                    </a>

                </div>





                <!-- ATTENDANCE MANAGEMENT -->

                <button
                    @click="attendance=!attendance"
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

                    <span x-text="attendance ? '⌄' : '›'"></span>

                </button>


                <div
                    x-show="attendance"
                    x-collapse
                    class="mt-1"
                >

                    @if(auth()->user()->hasPermission('view_attendance'))

                        <a
                            href="{{ route('attendance.monitor') }}"
                            class="{{ $child }} {{ request()->routeIs('attendance.monitor') ? $active : '' }}"
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Attendance Monitoring

                        </a>


                        <a
                            href="{{ route('attendance.alerts') }}"
                            class="{{ $child }} {{ request()->routeIs('attendance.alerts') ? $active : '' }}"
                        >

                            <span class="text-[#D4A017]">
                                •
                            </span>

                            Attendance Alerts

                        </a>

                    @endif



                    <a
                        href="{{ route('participation.records') }}"
                        class="{{ $child }} {{ request()->routeIs('participation.records') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participation Records

                    </a>


                    <a
                        href="{{ route('participation.evaluation') }}"
                        class="{{ $child }} {{ request()->routeIs('participation.evaluation') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participation Evaluation

                    </a>

                </div>





                <!-- REPORTS -->

                <button
                    @click="reports=!reports"
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
                        class="{{ $child }} {{ request()->routeIs('reports.attendance') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Attendance Reports

                    </a>


                    <a
                        href="{{ route('reports.participation') }}"
                        class="{{ $child }} {{ request()->routeIs('reports.participation') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Participation Reports

                    </a>

                </div>





                <!-- SYSTEM MANAGEMENT -->

                <button
                    @click="system=!system"
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
                        class="{{ $child }} {{ request()->routeIs('logs.*') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Activity Logs

                    </a>


                    <a
                        href="{{ route('backup.index') }}"
                        class="{{ $child }} {{ request()->routeIs('backup.*') ? $active : '' }}"
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
                    class="{{ $link }} {{ request()->is('dashboard') ? $active : '' }}"
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




                <!-- EVENT MANAGEMENT -->

                <button
                    @click="event=!event"
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

                    <a
                        href="{{ route('events.index') }}"
                        class="{{ $child }} {{ request()->routeIs('events.*') ? $active : '' }}"
                    >

                        <span class="text-[#D4A017]">
                            •
                        </span>

                        Events

                    </a>

                </div>


            @endif





            <!-- ================================================= -->
            <!-- ATTENDANCE PERSONNEL -->
            <!-- ================================================= -->

            @if($role === 'Attendance Personnel')


                <!-- DASHBOARD -->

                <a
                    href="/dashboard"
                    class="{{ $link }} {{ request()->is('dashboard') ? $active : '' }}"
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




                <!-- MY ASSIGNED EVENTS -->

                <a
                    href="{{ route('my-events.index') }}"
                    class="{{ $link }} mt-3 {{
                        request()->routeIs('my-events.*') ||
                        request()->routeIs('attendance.index')
                            ? $active
                            : ''
                    }}"
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
                        My Assigned Events
                    </span>

                </a>


            @endif


        </nav>


    </div>

</aside>