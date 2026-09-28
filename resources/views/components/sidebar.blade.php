<aside class="fixed left-0 top-0 h-screen w-72 bg-[#101064] text-white shadow-xl">

<div class="flex flex-col h-full">


<div class="px-6 py-5 border-b border-white/10">

<div class="flex items-center gap-3">

<img src="{{ asset('images/logo.png') }}"
class="w-12 h-12 object-contain">

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



<nav
x-data="{
user: {{ request()->routeIs('personnel.*') || request()->routeIs('roles.*') ? 'true':'false' }},
student: {{ request()->routeIs('students.*') || request()->routeIs('rfid.*') ? 'true':'false' }},
event: {{ request()->routeIs('events.*') || request()->routeIs('announcements.*') ? 'true':'false' }},
activity: {{ request()->routeIs('attendance.*') || request()->routeIs('participation.*') ? 'true':'false' }},
reports: {{ request()->routeIs('reports.*') ? 'true':'false' }},
system: {{ request()->routeIs('logs.*') || request()->routeIs('backup.*') ? 'true':'false' }}
}"
class="flex-1 px-4 py-5 overflow-y-auto">


@php

$role = auth()->user()->role->role_name;

$link = "flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition hover:bg-white/10";

$child = "flex items-center gap-3 ml-5 px-3 py-2 rounded-lg text-sm transition hover:bg-white/10";

$active = "bg-[#D4A017]/20 text-[#D4A017]";

@endphp



@if($role === 'Administrator')



<!-- DASHBOARD -->

<a href="/dashboard"
class="{{ $link }} {{ request()->is('dashboard') ? $active : '' }}">

<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M3 12l9-9 9 9M5 10v10h14V10"/>
</svg>

<span>Dashboard</span>

</a>



<!-- USER MANAGEMENT -->

<button
@click="user=!user"
class="{{ $link }} w-full mt-4">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M17 20h5v-2a4 4 0 0 0-4-4h-1M9 20H4v-2a4 4 0 0 1 4-4h1m4-4a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/>
</svg>


<span class="flex-1 text-left">
User Management
</span>


<span x-text="user ? '⌄':'›'"></span>


</button>



<div x-show="user" x-collapse class="mt-1">


@if(auth()->user()->hasPermission('manage_personnel_accounts'))

<a href="{{ route('personnel.index') }}"
class="{{ $child }} {{ request()->routeIs('personnel.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Personnel Management

</a>

@endif



@if(auth()->user()->hasPermission('assign_roles'))

<a href="{{ route('roles.index') }}"
class="{{ $child }} {{ request()->routeIs('roles.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Roles & Access

</a>

@endif


</div>

<!-- STUDENT MANAGEMENT -->


<button
@click="student=!student"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zM12 14l6.16-3.42M12 14v7"/>
</svg>


<span class="flex-1 text-left">
Student Management
</span>


<span x-text="student ? '⌄':'›'"></span>


</button>



<div x-show="student" x-collapse class="mt-1">


@if(auth()->user()->hasPermission('import_student_data'))

<a href="{{ route('students.index') }}"
class="{{ $child }} {{ request()->routeIs('students.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Student Data

</a>

@endif



<a href="{{ route('rfid.index') }}"
class="{{ $child }} {{ request()->routeIs('rfid.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

RFID Management

</a>


</div>






<!-- EVENT MANAGEMENT -->


<button
@click="event=!event"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

<path stroke-width="2"
d="M8 7V3m8 4V3M5 11h14M5 19h14M5 7h14v14H5z"/>

</svg>


<span class="flex-1 text-left">
Event Management
</span>


<span x-text="event ? '⌄':'›'"></span>


</button>



<div x-show="event" x-collapse class="mt-1">


<a href="{{ route('events.index') }}"
class="{{ $child }} {{ request()->routeIs('events.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Events

</a>



<a href="{{ route('announcements.index') }}"
class="{{ $child }} {{ request()->routeIs('announcements.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Announcements

</a>


</div>

<!-- STUDENT ACTIVITY MANAGEMENT -->


<button
@click="activity=!activity"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2"
d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>


<span class="flex-1 text-left">
Student Activity Management
</span>


<span x-text="activity ? '⌄':'›'"></span>


</button>



<div x-show="activity" x-collapse class="mt-1">



<a href="{{ route('attendance.index') }}"
class="{{ $child }} {{ request()->routeIs('attendance.index') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Attendance Records

</a>




<a href="{{ route('attendance.monitor') }}"
class="{{ $child }} {{ request()->routeIs('attendance.monitor') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Attendance Monitoring

</a>




<a href="{{ route('attendance.alerts') }}"
class="{{ $child }} {{ request()->routeIs('attendance.alerts') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Attendance Alerts

</a>





<a href="{{ route('participation.records') }}"
class="{{ $child }} {{ request()->routeIs('participation.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Participation Records

</a>




<a href="{{ route('participation.evaluation') }}"
class="{{ $child }} {{ request()->routeIs('participation.evaluation') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Participation Evaluation

</a>



</div>







<!-- REPORTS & ANALYTICS -->


<button
@click="reports=!reports"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

<path stroke-width="2"
d="M3 3v18h18M7 16v-5m5 5V8m5 8V5"/>

</svg>


<span class="flex-1 text-left">
Reports & Analytics
</span>


<span x-text="reports ? '⌄':'›'"></span>


</button>



<div x-show="reports" x-collapse class="mt-1">



<a href="{{ route('reports.attendance') }}"
class="{{ $child }} {{ request()->routeIs('reports.attendance') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Attendance Reports

</a>




<a href="{{ route('reports.participation') }}"
class="{{ $child }} {{ request()->routeIs('reports.participation') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Participation Reports

</a>



</div>







<!-- SYSTEM MANAGEMENT -->


<button
@click="system=!system"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

<path stroke-width="2"
d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1 1.54V21a2 2 0 1 1-4 0v-.09a1.7 1.7 0 0 0-1-1.54 1.7 1.7 0 0 0-1.88.34l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06A1.7 1.7 0 0 0 4.6 15a1.7 1.7 0 0 0-1.54-1H3a2 2 0 1 1 0-4h.09a1.7 1.7 0 0 0 1.54-1 1.7 1.7 0 0 0-.34-1.88l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.54V3a2 2 0 1 1 4 0v.09a1.7 1.7 0 0 0 1 1.54 1.7 1.7 0 0 0 1.88-.34l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.7 1.7 0 0 0-.34 1.88 1.7 1.7 0 0 0 1.54 1H21a2 2 0 1 1 0 4h-.09a1.7 1.7 0 0 0-1.51 1z"/>

</svg>


<span class="flex-1 text-left">
System Management
</span>


<span x-text="system ? '⌄':'›'"></span>


</button>



<div x-show="system" x-collapse class="mt-1">



<a href="{{ route('logs.index') }}"
class="{{ $child }} {{ request()->routeIs('logs.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Activity Logs

</a>




<a href="{{ route('backup.index') }}"
class="{{ $child }} {{ request()->routeIs('backup.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Backup & Recovery

</a>



</div>

@elseif($role === 'Attendance Personnel')


<!-- ATTENDANCE PERSONNEL -->


<a href="/dashboard"
class="{{ $link }} {{ request()->is('dashboard') ? $active : '' }}">

<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M3 12l9-9 9 9M5 10v10h14V10"/>
</svg>

Dashboard

</a>



<button
@click="activity=!activity"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
</svg>


<span class="flex-1 text-left">
Student Activity
</span>


<span x-text="activity ? '⌄':'›'"></span>


</button>




<div x-show="activity" x-collapse class="mt-1">



<a href="{{ route('my-events.index') }}"
class="{{ $child }} {{ request()->routeIs('my-events.*') ? $active : '' }}">

<span class="text-[#D4A017]">•</span>

My Assigned Events

</a>





@if(auth()->user()->hasPermission('view_attendance'))

<a href="{{ route('attendance.index') }}"
class="{{ $child }} {{ request()->routeIs('attendance.*') ? $active : '' }}">

<span class="text-[#D4A017]">•</span>

Attendance Scanner

</a>

@endif



</div>







@elseif($role === 'Registrar Staff')



<!-- REGISTRAR STAFF -->


<a href="/dashboard"
class="{{ $link }} {{ request()->is('dashboard') ? $active : '' }}">

<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M3 12l9-9 9 9M5 10v10h14V10"/>
</svg>

Dashboard

</a>





<button
@click="student=!student"
class="{{ $link }} w-full mt-3">


<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
</svg>


<span class="flex-1 text-left">
Student Management
</span>


<span x-text="student ? '⌄':'›'"></span>


</button>





<div x-show="student" x-collapse class="mt-1">



@if(auth()->user()->hasPermission('import_student_data'))

<a href="{{ route('students.index') }}"
class="{{ $child }} {{ request()->routeIs('students.*') ? $active : '' }}">


<span class="text-[#D4A017]">•</span>

Student Data

</a>

@endif



</div>




@endif



</nav>


</div>

</aside>