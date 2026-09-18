<x-admin-layout>

<div class="space-y-8">


<!-- HEADER -->

<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Good day, {{ auth()->user()->name }}

</h1>


<p class="
mt-2
text-sm
text-gray-500
">

Your assigned attendance operations for today.

</p>

</div>








<!-- CURRENT EVENT -->

<div class="
bg-[#101064]
rounded-2xl
p-8
text-white
shadow-sm
">


<div class="
flex
justify-between
items-start
">


<div>


<p class="
text-xs
uppercase
tracking-wider
text-blue-200
">

Current Assigned Event

</p>



<h2 class="
mt-3
text-3xl
font-bold
">

Freshmen Orientation 2026

</h2>



<div class="
mt-5
space-y-2
text-sm
text-blue-100
">


<p>
Date: September 25, 2026
</p>


<p>
Time: 8:00 AM - 11:00 AM
</p>


<p>
Venue: Main Auditorium
</p>


</div>


</div>




<div>

<span class="
rounded-full
bg-[#D4A017]
px-4
py-2
text-sm
font-semibold
text-[#101064]
">

Upcoming

</span>


</div>


</div>





<div class="
mt-8
">


<a href="#"
class="
inline-flex
items-center
rounded-xl
bg-white
px-6
py-3
font-semibold
text-[#101064]
transition
hover:bg-gray-100
"
>

Start Attendance

</a>


</div>


</div>









<!-- ATTENDANCE SUMMARY -->


<div class="
grid
grid-cols-1
md:grid-cols-3
gap-6
">



<div class="
bg-white
rounded-2xl
border
border-gray-200
p-6
shadow-sm
">


<p class="
text-sm
text-gray-500
">

Registered Students

</p>


<h2 class="
mt-3
text-4xl
font-bold
text-[#101064]
">

500

</h2>


</div>





<div class="
bg-white
rounded-2xl
border
border-gray-200
p-6
shadow-sm
">


<p class="
text-sm
text-gray-500
">

Present

</p>


<h2 class="
mt-3
text-4xl
font-bold
text-green-600
">

342

</h2>


</div>





<div class="
bg-white
rounded-2xl
border
border-gray-200
p-6
shadow-sm
">


<p class="
text-sm
text-gray-500
">

Absent

</p>


<h2 class="
mt-3
text-4xl
font-bold
text-red-500
">

158

</h2>


</div>


</div>

<!-- RECENT RFID SCANS -->


<div class="
bg-white
rounded-2xl
border
border-gray-200
shadow-sm
overflow-hidden
">


<div class="
px-6
py-5
border-b
border-gray-200
">


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Recent RFID Scans

</h2>


<p class="
mt-1
text-sm
text-gray-500
">

Latest attendance records from your active event.

</p>


</div>





<div class="
overflow-x-auto
">


<table class="
w-full
text-left
">


<thead class="
bg-gray-50
">


<tr>


<th class="
px-6
py-4
text-xs
uppercase
tracking-wide
text-gray-500
">

Student ID

</th>


<th class="
px-6
py-4
text-xs
uppercase
tracking-wide
text-gray-500
">

Student Name

</th>


<th class="
px-6
py-4
text-xs
uppercase
tracking-wide
text-gray-500
">

Time Scanned

</th>


<th class="
px-6
py-4
text-xs
uppercase
tracking-wide
text-gray-500
">

Status

</th>


</tr>


</thead>






<tbody>


<tr class="
border-t
">


<td class="
px-6
py-4
text-sm
text-gray-700
">

2026-00124

</td>


<td class="
px-6
py-4
text-sm
font-medium
text-[#101064]
">

Angela Ramos

</td>


<td class="
px-6
py-4
text-sm
text-gray-600
">

8:03 AM

</td>


<td class="
px-6
py-4
">


<span class="
rounded-full
bg-green-100
px-3
py-1
text-xs
font-medium
text-green-700
">

Present

</span>


</td>


</tr>







<tr class="
border-t
">


<td class="
px-6
py-4
text-sm
text-gray-700
">

2026-00157

</td>


<td class="
px-6
py-4
text-sm
font-medium
text-[#101064]
">

Michael Torres

</td>


<td class="
px-6
py-4
text-sm
text-gray-600
">

8:05 AM

</td>


<td class="
px-6
py-4
">


<span class="
rounded-full
bg-green-100
px-3
py-1
text-xs
font-medium
text-green-700
">

Present

</span>


</td>


</tr>







<tr class="
border-t
">


<td class="
px-6
py-4
text-sm
text-gray-700
">

2026-00201

</td>


<td class="
px-6
py-4
text-sm
font-medium
text-[#101064]
">

Sofia Mendoza

</td>


<td class="
px-6
py-4
text-sm
text-gray-600
">

8:07 AM

</td>


<td class="
px-6
py-4
">


<span class="
rounded-full
bg-green-100
px-3
py-1
text-xs
font-medium
text-green-700
">

Present

</span>


</td>


</tr>


</tbody>


</table>


</div>


</div>









<!-- UPCOMING EVENTS -->


<div class="
bg-white
rounded-2xl
border
border-gray-200
p-6
shadow-sm
">


<div class="
flex
items-center
justify-between
">


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Upcoming Assigned Events

</h2>


<p class="
mt-1
text-sm
text-gray-500
">

Events assigned to you in the future.

</p>


</div>





<a href="{{ route('my-events.index') }}"

class="
text-sm
font-medium
text-[#D4A017]
hover:underline
"
>

View All

</a>



</div>






<div class="
mt-5
space-y-3
">


<div class="
rounded-xl
border
border-gray-200
p-4
flex
justify-between
items-center
">


<div>


<h3 class="
font-semibold
text-[#101064]
">

Leadership Training Seminar

</h3>


<p class="
text-sm
text-gray-500
mt-1
">

October 3, 2026 • AVR 2

</p>


</div>


<span class="
rounded-full
bg-blue-100
px-3
py-1
text-xs
font-medium
text-blue-700
">

Scheduled

</span>


</div>






<div class="
rounded-xl
border
border-gray-200
p-4
flex
justify-between
items-center
">


<div>


<h3 class="
font-semibold
text-[#101064]
">

College Assembly

</h3>


<p class="
text-sm
text-gray-500
mt-1
">

October 10, 2026 • Conference Hall

</p>


</div>


<span class="
rounded-full
bg-blue-100
px-3
py-1
text-xs
font-medium
text-blue-700
">

Scheduled

</span>


</div>



</div>


</div>






</div>

</x-admin-layout>