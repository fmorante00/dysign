<x-admin-layout>

<div class="space-y-10">



<!-- EVENT HEADER -->

<section
class="
bg-[#101064]
rounded-3xl
p-10
text-white
relative
overflow-hidden
"
>


<div
class="
absolute
right-0
top-0
h-64
w-64
rounded-full
bg-white/10
translate-x-20
-translate-y-20
"
></div>



<div
class="
relative
z-10
flex
flex-col
lg:flex-row
lg:items-center
lg:justify-between
gap-8
"
>



<div>


<p
class="
uppercase
tracking-[0.35em]
text-sm
text-blue-200
"
>
Event Details
</p>



<h1
class="
mt-3
text-5xl
font-bold
"
>
{{ $event->event_name }}
</h1>





<div
class="
mt-5
flex
items-center
gap-3
"
>


@if($event->status === 'Ongoing')

<span
class="
bg-green-400/20
text-green-200
px-4
py-2
rounded-full
font-semibold
"
>
Ongoing
</span>


@elseif($event->status === 'Upcoming')


<span
class="
bg-yellow-400/20
text-yellow-200
px-4
py-2
rounded-full
font-semibold
"
>
Upcoming
</span>


@elseif($event->status === 'Completed')


<span
class="
bg-white/20
text-white
px-4
py-2
rounded-full
font-semibold
"
>
Completed
</span>


@else


<span
class="
bg-red-400/20
text-red-200
px-4
py-2
rounded-full
font-semibold
"
>
Cancelled
</span>


@endif


</div>




</div>








<!-- START BUTTON -->


@if($canScan)


<a
href="{{ route('attendance.index',$event->event_id) }}"
class="
inline-flex
items-center
justify-center
gap-3
bg-[#D4A017]
text-[#101064]
px-8
py-4
rounded-2xl
font-bold
text-lg
transition
hover:bg-white
"
>





<path
stroke-width="2"
stroke-linecap="round"
stroke-linejoin="round"
d="M13 10V3L4 14h7v7l9-11h-7z"
/>

</svg>


Start Attendance


</a>


@endif





</div>


</section>

<!-- SUMMARY -->

<section class="grid grid-cols-1 gap-6 md:grid-cols-3">

    <!-- ATTENDANCE RECORDED -->
    <div class="border-l-4 border-[#D4A017] bg-white px-6 py-5">

        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
            Attendance Recorded
        </p>

        <h2 class="mt-2 text-4xl font-bold text-[#101064]">
            {{ $attendanceCount }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ $attendanceCount === 1 ? 'Student recorded' : 'Students recorded' }}
        </p>

    </div>


    <!-- SCANNER STATUS -->
    <div class="border-l-4 border-[#101064] bg-white px-6 py-5">

        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
            Scanner
        </p>

        <h2 class="mt-2 text-xl font-bold {{ $canScan ? 'text-green-600' : 'text-gray-500' }}">
            {{ $canScan ? 'Available' : 'Closed' }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ $canScan ? 'RFID attendance may be recorded' : 'Scanning is unavailable' }}
        </p>

    </div>


    <!-- DEPARTMENT -->
    <div class="border-l-4 border-gray-300 bg-white px-6 py-5">

        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">
            Department
        </p>

        <h2 class="mt-2 text-lg font-bold text-[#101064]">
            {{ $event->department->department_name ?? 'University-wide Event' }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Assigned event
        </p>

    </div>

</section>



<!-- INFORMATION -->

<section
class="
grid
grid-cols-1
lg:grid-cols-2
gap-8
"
>






<!-- EVENT INFORMATION -->


<div
class="
bg-white
rounded-3xl
border
border-gray-200
p-8
"
>


<h2
class="
text-2xl
font-bold
text-[#101064]
mb-6
"
>
Event Information
</h2>




<div class="space-y-6">



<div>

<p class="text-sm text-gray-400 uppercase">
Department
</p>


<p class="mt-1 font-semibold">
{{ $event->department->department_name ?? 'University Event' }}
</p>


</div>




<div>

<p class="text-sm text-gray-400 uppercase">
Venue
</p>


<p class="mt-1 font-semibold">
{{ $event->location }}
</p>


</div>





<div>

<p class="text-sm text-gray-400 uppercase">
Date
</p>


<p class="mt-1 font-semibold">

{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}

</p>


</div>



</div>



</div>








<!-- SCHEDULE -->


<div
class="
bg-white
rounded-3xl
border
border-gray-200
p-8
"
>


<h2
class="
text-2xl
font-bold
text-[#101064]
mb-6
"
>
Schedule
</h2>




<div
class="
border-l-4
border-[#D4A017]
pl-6
space-y-5
"
>


<div>

<p
class="
text-sm
text-gray-400
"
>
Starts
</p>


<p
class="
text-2xl
font-bold
text-[#101064]
"
>

{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}

</p>


</div>





<div>

<p
class="
text-sm
text-gray-400
"
>
Ends
</p>


<p
class="
text-2xl
font-bold
text-[#101064]
"
>

{{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}

</p>


</div>



</div>




</div>





</section>









<!-- BACK -->

<div>


<a
href="{{ route('my-events.index') }}"
class="
text-[#101064]
font-semibold
hover:text-[#D4A017]
"
>

← Back to Assigned Events

</a>


</div>




</div>

</x-admin-layout>