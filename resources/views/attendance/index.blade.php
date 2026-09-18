<x-admin-layout>


<div class="space-y-8">



<!-- HEADER -->

<div>


<h1 class="
text-3xl
font-bold
text-[#101064]
">

Attendance Scanner

</h1>


<p class="
mt-2
text-gray-500
">

Monitor RFID attendance for your assigned event.

</p>


</div>









<!-- EVENT STATUS -->


<div class="
bg-[#101064]
rounded-3xl
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
text-sm
uppercase
tracking-wide
text-blue-200
">

Current Event

</p>



<h2 class="
mt-2
text-3xl
font-bold
">

Leadership Seminar

</h2>



<p class="
mt-4
text-blue-100
">

September 25, 2026 • Main Auditorium

</p>


</div>






<span class="
px-4
py-2
rounded-full
bg-[#D4A017]
text-[#101064]
font-semibold
text-sm
">

Ready

</span>



</div>


</div>









<!-- SCANNER AREA -->


<div class="
grid
grid-cols-1
lg:grid-cols-3
gap-6
">





<!-- RFID STATUS -->


<div class="
lg:col-span-2
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-10
text-center
">


<div class="
mx-auto
w-24
h-24
rounded-full
bg-[#101064]
flex
items-center
justify-center
text-white
text-4xl
mb-6
">

◉

</div>





<h2 class="
text-2xl
font-bold
text-[#101064]
">

Waiting for RFID Scan

</h2>




<p class="
mt-3
text-gray-500
">

Place student ID card near the RFID scanner.

</p>





<div class="
mt-8
bg-gray-50
rounded-2xl
p-6
">


<p class="
text-sm
text-gray-500
">

Scanner Status

</p>



<p class="
mt-2
font-semibold
text-green-600
">

Connected

</p>



</div>




</div>









<!-- SUMMARY -->


<div class="
space-y-6
">





<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Registered Students

</p>


<h2 class="
text-4xl
font-bold
text-[#101064]
mt-3
">

500

</h2>


</div>







<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Present Today

</p>


<h2 class="
text-4xl
font-bold
text-green-600
mt-3
">

342

</h2>


</div>







<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Remaining

</p>


<h2 class="
text-4xl
font-bold
text-gray-700
mt-3
">

158

</h2>


</div>


</div>



</div>

<!-- LATEST SCAN PREVIEW -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
p-8
">


<div class="
flex
justify-between
items-center
mb-6
">


<div>


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Latest Scan

</h2>


<p class="
mt-1
text-sm
text-gray-500
">

Most recent RFID attendance record.

</p>


</div>



<span class="
px-4
py-2
rounded-full
bg-green-100
text-green-700
text-sm
font-semibold
">

Recorded

</span>



</div>







<div class="
grid
grid-cols-1
md:grid-cols-4
gap-6
">



<div>

<p class="
text-sm
text-gray-500
">

Student ID

</p>


<p class="
mt-2
font-semibold
text-[#101064]
">

2026-00124

</p>


</div>






<div>

<p class="
text-sm
text-gray-500
">

Student Name

</p>


<p class="
mt-2
font-semibold
text-[#101064]
">

Angela Ramos

</p>


</div>






<div>

<p class="
text-sm
text-gray-500
">

Program

</p>


<p class="
mt-2
font-semibold
text-gray-700
">

BSIT

</p>


</div>






<div>

<p class="
text-sm
text-gray-500
">

Time

</p>


<p class="
mt-2
font-semibold
text-gray-700
">

8:03 AM

</p>


</div>



</div>



</div>









<!-- LIVE ATTENDANCE TABLE -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
overflow-hidden
">


<div class="
p-8
border-b
border-gray-100
">


<h2 class="
text-xl
font-semibold
text-[#101064]
">

Live Attendance Records

</h2>


<p class="
mt-1
text-sm
text-gray-500
">

Students recorded for the current event.

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

Name

</th>


<th class="
px-6
py-4
text-xs
uppercase
tracking-wide
text-gray-500
">

Program

</th>


<th class="
px-6
py-4
text-xs
uppercase
tracking-wide
text-gray-500
">

Time

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

BSIT

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
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
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

BSA

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
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Present

</span>


</td>


</tr>



</tbody>



</table>


</div>



</div>









<!-- BACK BUTTON -->


<div class="
flex
justify-end
">


<a

href="{{ route('my-events.index') }}"

class="
px-6
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
transition
"

>

Back to Assigned Events

</a>



</div>






</div>


</x-admin-layout>