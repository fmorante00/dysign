<x-admin-layout>

<div class="space-y-8">


<!-- HEADER -->

<div class="flex justify-between items-center">


<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Leadership Seminar

</h1>


<p class="
mt-2
text-gray-500
">

Live RFID attendance monitoring

</p>


</div>





<a

href="{{ route('events.show',1) }}"

class="
px-5
py-3
rounded-xl
border
border-gray-300
text-gray-600
hover:bg-gray-50
"

>

Back to Event

</a>


</div>









<!-- STATUS AREA -->


<div class="
grid
grid-cols-1
md:grid-cols-3
gap-5
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

Event Status

</p>


<div class="
mt-3
inline-flex
px-4
py-2
rounded-full
bg-green-50
text-green-700
font-semibold
">

ACTIVE

</div>


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

Scanner Status

</p>


<div class="
mt-3
inline-flex
px-4
py-2
rounded-full
bg-green-50
text-green-700
font-semibold
">

● Connected

</div>


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

Current Time

</p>


<h2 class="
text-2xl
font-bold
text-[#101064]
mt-3
">

08:05 AM

</h2>


</div>





</div>









<!-- SCANNER DISPLAY -->


<div class="
bg-[#101064]
rounded-3xl
p-10
text-center
text-white
">


<div class="
mx-auto
w-20
h-20
rounded-full
bg-white/10
flex
items-center
justify-center
text-4xl
mb-5
">

RFID

</div>




<h2 class="
text-2xl
font-bold
">

Ready for Scan

</h2>


<p class="
mt-3
text-white/70
">

Waiting for student RFID identification...

</p>



</div>









<!-- LIVE ATTENDANCE -->


<div class="
bg-white
rounded-3xl
border
border-gray-100
shadow-sm
">


<div class="
p-6
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
text-sm
text-gray-500
mt-1
">

Recent RFID scans during the event.

</p>


</div>







<table class="w-full">


<thead class="bg-gray-50">


<tr>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Time

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Student

</th>


<th class="
px-6
py-4
text-left
text-sm
text-gray-500
">

Program

</th>


<th class="
px-6
py-4
text-left
text-sm
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

<td class="px-6 py-5">

08:01 AM

</td>


<td class="px-6 py-5">


<p class="font-semibold text-[#101064]">

Juan Dela Cruz

</p>


<p class="text-sm text-gray-500">

2026-0001

</p>


</td>


<td class="px-6 py-5">

BSIT

</td>


<td class="px-6 py-5">


<span class="
px-3
py-1
rounded-full
bg-green-50
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

<td class="px-6 py-5">

08:02 AM

</td>


<td class="px-6 py-5">


<p class="font-semibold text-[#101064]">

Maria Santos

</p>


<p class="text-sm text-gray-500">

2026-0002

</p>


</td>


<td class="px-6 py-5">

BSACC

</td>


<td class="px-6 py-5">


<span class="
px-3
py-1
rounded-full
bg-green-50
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


</x-admin-layout>