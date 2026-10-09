<x-admin-layout>

<div class="space-y-10">


<!-- PAGE HEADER -->

<section>

<div class="flex items-start justify-between">


<div>

<p class="text-xs uppercase tracking-[0.35em] text-[#D4A017]">
Attendance Operations
</p>


<h1 class="mt-3 text-4xl font-bold text-[#101064]">
My Assigned Events
</h1>


<p class="mt-2 text-gray-500">
Manage assigned events and monitor attendance activities.
</p>


</div>


</div>

</section>








<!-- SUMMARY -->

<section
class="
grid
grid-cols-1
md:grid-cols-3
gap-6
"
>


<!-- ONGOING -->

<div
class="
bg-[#101064]
rounded-3xl
p-7
text-white
relative
overflow-hidden
"
>


<div
class="
absolute
right-5
top-5
w-20
h-20
rounded-full
bg-white/10
"
></div>


<p
class="
text-sm
uppercase
tracking-wider
text-blue-200
"
>
Ongoing
</p>


<h2
class="
mt-4
text-5xl
font-bold
"
>
{{ $events->where('status','Ongoing')->count() }}
</h2>


<p
class="
mt-2
text-blue-100
"
>
Active attendance sessions
</p>


</div>







<!-- UPCOMING -->


<div
class="
bg-white
rounded-3xl
border
border-gray-200
p-7
"
>


<p
class="
text-sm
uppercase
tracking-wider
text-gray-400
"
>
Upcoming
</p>


<h2
class="
mt-4
text-5xl
font-bold
text-[#101064]
"
>
{{ $events->where('status','Upcoming')->count() }}
</h2>


<p
class="
mt-2
text-gray-500
"
>
Scheduled events
</p>


</div>







<!-- COMPLETED -->


<div
class="
bg-white
rounded-3xl
border
border-gray-200
p-7
"
>


<p
class="
text-sm
uppercase
tracking-wider
text-gray-400
"
>
Completed
</p>


<h2
class="
mt-4
text-5xl
font-bold
text-[#101064]
"
>
{{ $events->where('status','Completed')->count() }}
</h2>


<p
class="
mt-2
text-gray-500
"
>
Finished attendance records
</p>


</div>



</section>









<!-- EVENTS -->

<section>


<div
class="
flex
items-center
justify-between
mb-6
"
>


<h2
class="
text-2xl
font-bold
text-[#101064]
"
>
Assigned Events
</h2>


<span
class="
text-sm
text-gray-400
"
>
{{ $events->count() }} Events
</span>


</div>







<div
class="
bg-white
rounded-3xl
border
border-gray-200
divide-y
divide-gray-100
overflow-hidden
"
>




@forelse($events as $event)



<div
class="
p-7
hover:bg-gray-50
transition
"
>



<div
class="
flex
flex-col
lg:flex-row
lg:items-center
lg:justify-between
gap-6
"
>



<!-- EVENT DETAILS -->

<div>


<div
class="
flex
items-center
gap-3
"
>



<h3
class="
text-2xl
font-bold
text-[#101064]
"
>
{{ $event->event_name }}
</h3>



@if($event->status === 'Ongoing')


<span
class="
rounded-full
bg-green-100
px-3
py-1
text-xs
font-semibold
text-green-700
"
>
Ongoing
</span>


@elseif($event->status === 'Upcoming')


<span
class="
rounded-full
bg-yellow-100
px-3
py-1
text-xs
font-semibold
text-yellow-700
"
>
Upcoming
</span>


@elseif($event->status === 'Completed')


<span
class="
rounded-full
bg-gray-100
px-3
py-1
text-xs
font-semibold
text-gray-700
"
>
Completed
</span>


@else


<span
class="
rounded-full
bg-red-100
px-3
py-1
text-xs
font-semibold
text-red-700
"
>
Cancelled
</span>


@endif



</div>







<p
class="
mt-3
text-gray-500
"
>
{{ $event->department->department_name ?? 'University-wide Event' }}
</p>






<div
class="
mt-4
flex
flex-wrap
gap-5
text-sm
text-gray-500
"
>


<span>
{{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
</span>


<span>
{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }}
-
{{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}
</span>


<span>
{{ $event->location }}
</span>


</div>




</div>








<!-- ACTION -->


<div
class="
flex
items-center
gap-5
"
>



<div
class="
text-right
hidden
md:block
"
>


<p
class="
text-sm
text-gray-400
"
>
Attendance
</p>


<p
class="
text-xl
font-bold
text-[#101064]
"
>
{{ $event->attendance_count ?? 0 }}
</p>


</div>







<a
href="{{ route('my-events.show',$event->event_id) }}"
class="
inline-flex
items-center
gap-2
rounded-xl
bg-[#101064]
px-6
py-3
font-semibold
text-white
transition
hover:bg-[#D4A017]
"
>

Open Event

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
d="M9 5l7 7-7 7"
/>

</svg>


</a>



</div>






</div>


</div>





@empty


<div
class="
p-10
text-center
text-gray-400
"
>
No assigned events found.
</div>


@endforelse





</div>


</section>







</div>

</x-admin-layout>