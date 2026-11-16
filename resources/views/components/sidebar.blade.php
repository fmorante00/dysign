{{--
    DySign • Unified Role-Aware Sidebar
    Presentation-only refactor: existing roles, route names, and permission checks retained.
    Activity Point Management is intentionally omitted from navigation; its backend is untouched.
    Keep w-72 / fixed positioning in sync with the existing admin layout.
--}}
@php
    $sidebarUser = auth()->user();
    $sidebarRole = $sidebarUser?->role?->role_name;

    // Preserve the existing optional dual-portal visibility rules.
    $showStudentNavigation = $sidebarRole === 'Student Account'
        || ($sidebarUser && method_exists($sidebarUser, 'canAccessStudentPortal') && $sidebarUser->canAccessStudentPortal());
    $showAttendanceNavigation = $sidebarRole === 'Attendance Personnel'
        || ($sidebarUser && method_exists($sidebarUser, 'canAccessAttendancePortal') && $sidebarUser->canAccessAttendancePortal());

    // A single coherent Lucide-style outline icon set (24x24, 1.8 stroke).
    // These are SVG path instructions only, with no external icon dependencies.
    $sidebarIcons = [
        'home'        => 'M3 10.5 12 3l9 7.5V21H3V10.5zM9 21v-7h6v7',
        'users'       => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
        'user-check'  => 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm8 0 2 2 4-4',
        'shield'      => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10zm-3-10 2 2 4-4',
        'graduation'  => 'M2 10l10-5 10 5-10 5-10-5zm4 2v5c3.6 3 8.4 3 12 0v-5m4-2v7',
        'id-card'     => 'M3 5h18v14H3zM7 10a2 2 0 1 0 4 0 2 2 0 0 0-4 0zm-1 6c0-2 6-2 6 0m3-6h3m-3 4h3',
        'calendar'    => 'M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z',
        'calendar-ok' => 'M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm4 12 2 2 4-4',
        'clipboard'   => 'M9 3h6l1 2h3v16H5V5h3l1-2zm-1 11 3 3 5-6',
        'check-list'  => 'M9 6h12m-12 6h12m-12 6h12M3 6l1 1 2-2m-3 7 1 1 2-2m-3 7 1 1 2-2',
        'users-round' => 'M16 19a4 4 0 0 0-8 0m4-7a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm7 7a4 4 0 0 0-3-3.87M16 4.13a4 4 0 0 1 0 7.75M5 19a4 4 0 0 1 3-3.87',
        'megaphone'   => 'M3 10v4h4l10 5V5L7 10H3zm4 4 2 6m8-10a4 4 0 0 1 0 4',
        'file-check'  => 'M6 3h9l4 4v14H6zM14 3v5h5M9 15l2 2 4-4',
        'file-text'   => 'M6 3h9l4 4v14H6zM14 3v5h5M9 12h7m-7 4h7',
        'chart'       => 'M4 20V4M4 20h16M8 16v-4m4 4V8m4 8V5',
        'chart-pie'   => 'M12 3v9h9M10 3a9 9 0 1 0 11 11M14 3a9 9 0 0 1 7 7',
        'chart-bars'  => 'M3 3v18h18M7 17v-6m5 6V8m5 9V5',
        'alert'       => 'M12 9v4m0 4h.01M10.3 3.9 2 18h19l-8-18a2 2 0 0 0-3.7 0z',
        'scan'        => 'M4 9V5a1 1 0 0 1 1-1h4m6 0h4a1 1 0 0 1 1 1v4M4 15v4a1 1 0 0 0 1 1h4m6 0h4a1 1 0 0 0 1-1v-4M4 12h16',
        'clock'       => 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 5v5l3 2',
        'clipboard-check' => 'M9 3h6l1 2h3v16H5V5h3l1-2zm-1 11 3 3 5-6',
        'settings'    => 'M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zm8-3.5 1-2-2-3-2 1-2-1-.5-2h-4L10 7l-2 1-2-1-2 3 1 2-1 2 2 3 2-1 2 1 .5 2h4l.5-2 2-1 2 1 2-3z',
        'history'     => 'M3 12a9 9 0 1 0 3-6.7M3 4v5h5m4-1v5l3 2',
        'database'    => 'M12 3c5 0 9 2 9 4s-4 4-9 4-9-2-9-4 4-4 9-4zm-9 4v10c0 2 4 4 9 4s9-2 9-4V7M3 12c0 2 4 4 9 4s9-2 9-4',
        'award'       => 'M12 15a6 6 0 1 0 0-12 6 6 0 0 0 0 12zm-4.5-1-1 8 5.5-3 5.5 3-1-8',
        'book-open'   => 'M12 6c-3-2-6-2-9-1v14c3-1 6-1 9 1m0-14c3-2 6-2 9-1v14c-3-1-6-1-9 1m0-14v14',
        'list'        => 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01',
    ];

    $sidebarNavigation = [
        ['kind' => 'link', 'label' => 'Dashboard', 'route' => 'dashboard', 'active' => ['dashboard'], 'icon' => 'home'],
    ];

    if (!in_array($sidebarRole, ['Administrator', 'Department Staff'], true)) {
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Analytics', 'route' => 'analytics.index', 'active' => ['analytics.*'], 'icon' => 'chart-pie'];
    }

    // Retain route and permission contract from the original four-role sidebar.
    if ($sidebarRole === 'Administrator') {
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Administration'];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'admin_users', 'label' => 'User Management', 'icon' => 'users', 'items' => [
            ['label' => 'Staff Accounts', 'route' => 'personnel.index', 'active' => ['personnel.*'], 'icon' => 'user-check', 'permission' => 'manage_personnel_accounts'],
        ]];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'admin_students', 'label' => 'Student Management', 'icon' => 'graduation', 'items' => [
            ['label' => 'Student Data', 'route' => 'students.index', 'active' => ['students.index', 'students.show', 'students.import', 'students.process-import'], 'icon' => 'graduation', 'permission' => 'import_student_data'],
            ['label' => 'Account Provisioning', 'route' => 'students.accounts.index', 'active' => ['students.accounts.*'], 'icon' => 'id-card'],
        ]];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'admin_events', 'label' => 'Event Management', 'icon' => 'calendar', 'items' => [
            ['label' => 'Events', 'route' => 'events.index', 'active' => ['events.index', 'events.create', 'events.show', 'events.edit'], 'icon' => 'calendar'],
            ['label' => 'Participant Eligibility', 'route' => 'events.eligibility.events', 'active' => ['events.eligibility.*'], 'icon' => 'users-round'],
        ]];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'admin_attendance', 'label' => 'Attendance Management', 'icon' => 'clipboard-check', 'items' => [
            ['label' => 'Attendance Monitoring', 'route' => 'attendance.monitor', 'active' => ['attendance.monitor'], 'icon' => 'scan', 'permission' => 'view_attendance'],
            ['label' => 'Participation Records', 'route' => 'participation.records', 'active' => ['participation.records'], 'icon' => 'list'],
            ['label' => 'Participation Evaluation', 'route' => 'participation.evaluation', 'active' => ['participation.evaluation'], 'icon' => 'chart'],
        ]];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Reports & System'];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'admin_reports', 'label' => 'Reports & Analytics', 'icon' => 'chart-bars', 'items' => [
            ['label' => 'Analytics', 'route' => 'analytics.index', 'active' => ['analytics.*'], 'icon' => 'chart-pie'],
            ['label' => 'Attendance Reports', 'route' => 'reports.attendance', 'active' => ['reports.attendance', 'reports.attendance.export'], 'icon' => 'chart-bars'],
            ['label' => 'Participation Reports', 'route' => 'reports.participation', 'active' => ['reports.participation', 'reports.participation.export'], 'icon' => 'chart-pie'],
        ]];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'admin_system', 'label' => 'System Management', 'icon' => 'settings', 'items' => [
            ['label' => 'Activity Logs', 'route' => 'logs.index', 'active' => ['logs.*'], 'icon' => 'history'],
            ['label' => 'Backup & Recovery', 'route' => 'backup.index', 'active' => ['backup.*'], 'icon' => 'database'],
        ]];
    }

    if ($sidebarRole === 'Department Staff') {
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Event Operations'];
        $sidebarNavigation[] = ['kind' => 'group', 'key' => 'staff_events', 'label' => 'Event Management', 'icon' => 'calendar', 'items' => [
            ['label' => 'Events', 'route' => 'events.index', 'active' => ['events.index', 'events.create', 'events.show', 'events.edit'], 'icon' => 'calendar'],
            ['label' => 'Participant Eligibility', 'route' => 'events.eligibility.events', 'active' => ['events.eligibility.*'], 'icon' => 'users-round'],
            ['label' => 'Personnel Assignment', 'route' => 'events.personnel-assignment.events', 'active' => ['events.personnel-assignment.*'], 'icon' => 'user-check'],
            ['label' => 'Announcements', 'route' => 'announcements.index', 'active' => ['announcements.*'], 'icon' => 'megaphone'],
            ['label' => 'Excuse Requests', 'route' => 'staff.excuse-requests.index', 'active' => ['staff.excuse-requests.*'], 'icon' => 'file-check'],
            ['label' => 'Evaluation Forms', 'route' => 'staff.evaluation-forms.index', 'active' => ['staff.evaluation-forms.*'], 'icon' => 'clipboard'],
            ['label' => 'Participant Registration', 'route' => 'events.registrations.index', 'active' => ['events.registrations.*'], 'icon' => 'calendar-ok'],
            ['label' => 'Evaluation Analysis', 'route' => 'staff.evaluation-analysis.index', 'active' => ['staff.evaluation-analysis.*'], 'icon' => 'chart-pie'],
        ]];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Attendance & Concerns'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Attendance Exceptions', 'route' => 'staff.attendance-exceptions.index', 'active' => ['staff.attendance-exceptions.*'], 'icon' => 'file-check'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Discrepancy Reviews', 'route' => 'staff.attendance-discrepancies.index', 'active' => ['staff.attendance-discrepancies.*'], 'icon' => 'alert'];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Participation & Reports'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Analytics', 'route' => 'analytics.index', 'active' => ['analytics.*'], 'icon' => 'chart-pie'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Participation Progress', 'route' => 'staff.participation-progress.index', 'active' => ['staff.participation-progress.*'], 'icon' => 'chart'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Event Attendance', 'route' => 'staff.participation-classifications.index', 'active' => ['staff.participation-classifications.*'], 'icon' => 'award'];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Personnel Coordination'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Personnel Availability', 'route' => 'personnel-availability.staff', 'active' => ['personnel-availability.staff'], 'icon' => 'calendar-ok'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Duty Monitoring', 'route' => 'personnel-duties.staff', 'active' => ['personnel-duties.staff'], 'icon' => 'shield'];
        // Activity Point Management intentionally hidden here, not deleted.
    }

    if ($showAttendanceNavigation) {
        // Avoid duplicate Dashboard when a user has both portals.
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Personnel Workspace'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Assigned Events / RFID Scanning', 'route' => 'my-events.index', 'active' => ['my-events.*', 'attendance.index'], 'icon' => 'scan'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Attendance Exceptions', 'route' => 'personnel.attendance-exceptions.index', 'active' => ['personnel.attendance-exceptions.*'], 'icon' => 'file-check'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Discrepancy Verification', 'route' => 'personnel.attendance-discrepancies.index', 'active' => ['personnel.attendance-discrepancies.*'], 'icon' => 'alert'];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'My Schedule'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Availability', 'route' => 'personnel-availability.index', 'active' => ['personnel-availability.index', 'personnel-availability.store', 'personnel-availability.update', 'personnel-availability.destroy'], 'icon' => 'calendar-ok'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Duties', 'route' => 'personnel-duties.index', 'active' => ['personnel-duties.index', 'personnel-duties.check-in', 'personnel-duties.check-out'], 'icon' => 'clipboard-check'];
    }

    if ($showStudentNavigation) {
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Events & Activities'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Event Registrations', 'route' => 'student.registrations.index', 'active' => ['student.registrations.*'], 'icon' => 'calendar-ok'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Evaluations', 'route' => 'student.evaluations.index', 'active' => ['student.evaluations.*'], 'icon' => 'clipboard'];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Attendance & Concerns'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Attendance History', 'route' => 'student.attendance-history.index', 'active' => ['student.attendance-history.*'], 'icon' => 'history'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Attendance Discrepancies', 'route' => 'student.attendance-discrepancies.index', 'active' => ['student.attendance-discrepancies.*'], 'icon' => 'alert'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Excuse Requests', 'route' => 'student.excuse-requests.index', 'active' => ['student.excuse-requests.*'], 'icon' => 'file-check'];
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Information & Progress'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Announcements', 'route' => 'student.announcements.index', 'active' => ['student.announcements.*'], 'icon' => 'megaphone'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'My Participation Progress', 'route' => 'student.participation-progress.index', 'active' => ['student.participation-progress.*'], 'icon' => 'chart'];
    }

    // Account Settings is an existing authenticated route from the teammate update.
    if (\Illuminate\Support\Facades\Route::has('settings.index')) {
        $sidebarNavigation[] = ['kind' => 'heading', 'label' => 'Account'];
        $sidebarNavigation[] = ['kind' => 'link', 'label' => 'Settings', 'route' => 'settings.index', 'active' => ['settings.*'], 'icon' => 'settings'];
    }

    $sidebarHasPermission = static fn(array $item): bool => !isset($item['permission'])
        || ($sidebarUser && $sidebarUser->hasPermission($item['permission']));
    $sidebarIsActive = static function (array $item): bool {
        foreach ((array) ($item['active'] ?? $item['route'] ?? []) as $pattern) {
            if (request()->routeIs($pattern)) return true;
        }
        return false;
    };
    $sidebarExpanded = [];
    foreach ($sidebarNavigation as $entry) {
        if (($entry['kind'] ?? '') !== 'group') continue;
        $sidebarExpanded[$entry['key']] = false;
        foreach ($entry['items'] as $item) {
            if ($sidebarHasPermission($item) && $sidebarIsActive($item)) {
                $sidebarExpanded[$entry['key']] = true;
                break;
            }
        }
    }
@endphp

<aside id="dysign-sidebar" class="fixed left-0 top-0 z-40 h-screen w-72 overflow-hidden bg-[#101064] text-white shadow-xl" aria-label="DySign sidebar">
    <style>
        #dysign-sidebar [x-cloak] { display: none !important; }
        #dysign-sidebar nav { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.20) transparent; }
        #dysign-sidebar nav::-webkit-scrollbar { width: 5px; }
        #dysign-sidebar nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.20); border-radius: 9999px; }
        /* Fixed pixel sizes: prevents oversized SVGs when Tailwind sizing utilities
           are unavailable or overridden in the InfinityFree production build. */
        #dysign-sidebar nav svg {
            display: block;
            width: 20px !important;
            height: 20px !important;
            min-width: 20px;
            max-width: 20px;
            min-height: 20px;
            max-height: 20px;
            flex: 0 0 20px;
        }
        #dysign-sidebar nav svg.ds-sidebar-sub-icon {
            width: 17px !important;
            height: 17px !important;
            min-width: 17px;
            max-width: 17px;
            min-height: 17px;
            max-height: 17px;
            flex: 0 0 17px;
        }
        #dysign-sidebar nav svg.ds-sidebar-chevron {
            width: 16px !important;
            height: 16px !important;
            min-width: 16px;
            max-width: 16px;
            min-height: 16px;
            max-height: 16px;
            flex: 0 0 16px;
        }
        /* Production-safe gold accents. Avoid relying on Tailwind's arbitrary
           color utilities, which may not be present in InfinityFree CSS. */
        #dysign-sidebar .ds-sidebar-brand-accent {
            color: #F2C44A !important;
        }
        #dysign-sidebar .ds-sidebar-section-label {
            color: rgba(242, 196, 74, 0.8) !important;
        }
        #dysign-sidebar nav .ds-sidebar-primary {
            border-left: 3px solid transparent;
        }
        #dysign-sidebar nav .ds-sidebar-primary[aria-current="page"],
        #dysign-sidebar nav .ds-sidebar-primary[data-ds-active="true"] {
            color: #F2C44A !important;
            border-left-color: #D4A017 !important;
        }
        #dysign-sidebar nav .ds-sidebar-primary[aria-current="page"] svg:not(.ds-sidebar-chevron),
        #dysign-sidebar nav .ds-sidebar-primary[data-ds-active="true"] svg:not(.ds-sidebar-chevron),
        #dysign-sidebar nav .ds-sidebar-sub-link[aria-current="page"],
        #dysign-sidebar nav .ds-sidebar-sub-link[aria-current="page"] svg {
            color: #F2C44A !important;
        }
    </style>
    <div class="flex h-full min-h-0 flex-col bg-gradient-to-b from-[#101064] via-[#11155b] to-[#0b1448]">
        {{-- Branding: keeps the same 18rem sidebar width as the current app shell. --}}
        <div class="relative shrink-0 overflow-hidden border-b border-white/10 px-5 pb-5 pt-6">
            <div class="pointer-events-none absolute -right-7 -top-10 h-32 w-32 rounded-full border border-white/10" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -right-12 top-2 h-28 w-28 rounded-full border border-[#D4A017]/20" aria-hidden="true"></div>
            <div class="relative flex items-center gap-3">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white/10 ring-1 ring-white/20">
                    <img src="{{ asset('images/logo.png') }}" class="h-10 w-10 object-contain" alt="DySign Logo">
                </div>
                <div class="min-w-0">
                    <p class="text-[21px] font-extrabold tracking-tight text-white">Dy<span class="ds-sidebar-brand-accent text-[#F2C44A]">Sign</span></p>
                    <p class="text-[10px] font-medium tracking-wide text-white/65">Digital Identity System</p>
                </div>
            </div>
        </div>

        <nav x-data='{ expanded: @json($sidebarExpanded) }'
             class="min-h-0 flex-1 space-y-1 overflow-y-auto px-3 pb-7 pt-5"
             aria-label="Main navigation">
            @foreach ($sidebarNavigation as $entry)
                @if ($entry['kind'] === 'heading')
                    <p class="ds-sidebar-section-label mb-1 mt-6 px-3 text-[10px] font-bold uppercase tracking-[0.14em] text-[#F2C44A]/80 first:mt-0">
                        {{ $entry['label'] }}
                    </p>
                @elseif ($entry['kind'] === 'group')
                    @php
                        $sidebarGroupItems = array_values(array_filter($entry['items'], $sidebarHasPermission));
                        $sidebarGroupActive = (bool) ($sidebarExpanded[$entry['key']] ?? false);
                        $sidebarGroupId = 'dysign-menu-' . $entry['key'];
                    @endphp
                    @if (count($sidebarGroupItems))
                        <button type="button"
                                @click="expanded['{{ $entry['key'] }}'] = !expanded['{{ $entry['key'] }}']"
                                :aria-expanded="expanded['{{ $entry['key'] }}'] ? 'true' : 'false'"
                                aria-controls="{{ $sidebarGroupId }}"
                                data-ds-active="{{ $sidebarGroupActive ? 'true' : 'false' }}"
                                class="ds-sidebar-primary group flex min-h-[43px] w-full items-center gap-3 rounded-xl border-l-[3px] px-3 py-2.5 text-left text-[13px] font-semibold leading-snug transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F2C44A] {{ $sidebarGroupActive ? 'border-[#D4A017] bg-white/10 text-[#F2C44A]' : 'border-transparent text-white/85 hover:bg-white/10 hover:text-white' }}">
                            <svg class="h-5 w-5 shrink-0 {{ $sidebarGroupActive ? 'text-[#F2C44A]' : 'text-white/70 group-hover:text-[#F2C44A]' }}"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $sidebarIcons[$entry['icon']] ?? $sidebarIcons['list'] }}" />
                            </svg>
                            <span class="min-w-0 flex-1">{{ $entry['label'] }}</span>
                            <svg class="ds-sidebar-chevron h-4 w-4 shrink-0 text-white/50 transition-transform duration-200"
                                 :class="expanded['{{ $entry['key'] }}'] ? 'rotate-180' : ''"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div id="{{ $sidebarGroupId }}" x-show="expanded['{{ $entry['key'] }}']" x-collapse x-cloak
                             class="mb-2 ml-[21px] border-l border-white/15 pl-2">
                            <div class="space-y-0.5 py-1">
                                @foreach ($sidebarGroupItems as $item)
                                    @php
                                        $sidebarReady = \Illuminate\Support\Facades\Route::has($item['route']);
                                        $sidebarActive = $sidebarReady && $sidebarIsActive($item);
                                    @endphp
                                    @if ($sidebarReady)
                                        <a href="{{ route($item['route']) }}"
                                           class="ds-sidebar-sub-link group flex min-h-[37px] items-center gap-2.5 rounded-lg px-2.5 py-2 text-[12px] font-medium leading-snug transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-[#F2C44A] {{ $sidebarActive ? 'bg-white/15 text-[#F2C44A]' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                                           @if ($sidebarActive) aria-current="page" @endif>
                                            <svg class="ds-sidebar-sub-icon h-[17px] w-[17px] shrink-0 {{ $sidebarActive ? 'text-[#F2C44A]' : 'text-white/55 group-hover:text-[#F2C44A]' }}"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="{{ $sidebarIcons[$item['icon']] ?? $sidebarIcons['list'] }}" />
                                            </svg>
                                            <span class="min-w-0 flex-1">{{ $item['label'] }}</span>
                                        </a>
                                    @else
                                        <div class="flex min-h-[37px] cursor-not-allowed items-center gap-2.5 rounded-lg px-2.5 py-2 text-[12px] leading-snug text-white/35"
                                             title="This DySign module is not installed yet" aria-disabled="true">
                                            <svg class="ds-sidebar-sub-icon h-[17px] w-[17px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="{{ $sidebarIcons[$item['icon']] ?? $sidebarIcons['list'] }}" />
                                            </svg>
                                            <span class="min-w-0 flex-1">{{ $item['label'] }}</span>
                                            <span class="rounded bg-white/10 px-1.5 py-0.5 text-[9px]">Soon</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                @elseif ($entry['kind'] === 'link' && $sidebarHasPermission($entry))
                    @php
                        $sidebarReady = \Illuminate\Support\Facades\Route::has($entry['route']);
                        $sidebarActive = $sidebarReady && $sidebarIsActive($entry);
                    @endphp
                    @if ($sidebarReady)
                        <a href="{{ route($entry['route']) }}"
                           class="ds-sidebar-primary group flex min-h-[43px] w-full items-center gap-3 rounded-xl border-l-[3px] px-3 py-2.5 text-[13px] font-semibold leading-snug transition-colors duration-150 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#F2C44A] {{ $sidebarActive ? 'border-[#D4A017] bg-white/15 text-[#F2C44A]' : 'border-transparent text-white/85 hover:bg-white/10 hover:text-white' }}"
                           @if ($sidebarActive) aria-current="page" @endif>
                            <svg class="h-5 w-5 shrink-0 {{ $sidebarActive ? 'text-[#F2C44A]' : 'text-white/70 group-hover:text-[#F2C44A]' }}"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $sidebarIcons[$entry['icon']] ?? $sidebarIcons['list'] }}" />
                            </svg>
                            <span class="min-w-0 flex-1">{{ $entry['label'] }}</span>
                        </a>
                    @else
                        <div class="flex min-h-[43px] cursor-not-allowed items-center gap-3 rounded-xl px-3 py-2.5 text-[13px] text-white/35"
                             title="This DySign module is not installed yet" aria-disabled="true">
                            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="{{ $sidebarIcons[$entry['icon']] ?? $sidebarIcons['list'] }}" />
                            </svg>
                            <span class="min-w-0 flex-1">{{ $entry['label'] }}</span>
                            <span class="rounded bg-white/10 px-1.5 py-0.5 text-[9px]">Soon</span>
                        </div>
                    @endif
                @endif
            @endforeach
        </nav>
        <div class="shrink-0 border-t border-white/10 bg-black/10 px-5 py-3">
            <div class="flex items-center justify-between gap-2">
                <p class="text-[10px] font-bold uppercase tracking-widest text-white/55">DySign Portal</p>
                <span class="h-1.5 w-1.5 rounded-full bg-[#D4A017]" aria-hidden="true"></span>
            </div>
        </div>
    </div>
</aside>
