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

RFID Identity Management

</h1>


<p class="
mt-2
text-gray-500
">

Manage RFID identifiers assigned to student records.

</p>


</div>


</div>









<!-- SUMMARY CARDS -->


<div class="
grid
grid-cols-1
md:grid-cols-4
gap-5
">



<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
">

<p class="text-sm text-gray-500">
Total RFID Cards
</p>

<h2 class="
text-3xl
font-bold
text-[#101064]
mt-2
">

1500

</h2>

</div>





<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
">

<p class="text-sm text-gray-500">
Assigned
</p>

<h2 class="
text-3xl
font-bold
text-green-600
mt-2
">

1200

</h2>

</div>





<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
">

<p class="text-sm text-gray-500">
Available
</p>

<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

250

</h2>

</div>





<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-5
">

<p class="text-sm text-gray-500">
Inactive
</p>

<h2 class="
text-3xl
font-bold
text-gray-600
mt-2
">

50

</h2>

</div>



</div>









<!-- RFID TABLE -->


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
flex
justify-between
items-center
">


<div>

<h2 class="
text-xl
font-semibold
text-[#101064]
">

RFID Directory

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

View assigned RFID identities.

</p>


</div>





<a

href="{{ route('rfid.assign') }}"

class="
px-5
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
"

>

+ Assign RFID

</a>


</div>








<div class="overflow-hidden">


<table class="w-full">


<thead class="bg-gray-50">


<tr>


<th class="px-6 py-4 text-left text-sm text-gray-500">
RFID UID
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Assigned Student
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Student ID
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Date Assigned
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Status
</th>


<th class="px-6 py-4 text-left text-sm text-gray-500">
Action
</th>


</tr>


</thead>

<tbody>


<!-- RFID RECORD 1 -->


<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
font-medium
text-gray-700
">

04-A8-93-XX-21

</td>





<td class="
px-6
py-6
">


<div class="
flex
items-center
gap-3
">


<div class="
w-10
h-10
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
">

J

</div>


<div>


<p class="
font-semibold
text-[#101064]
">

Juan Dela Cruz

</p>


<p class="
text-sm
text-gray-500
">

BS Information Technology

</p>


</div>


</div>


</td>







<td class="
px-6
py-6
text-gray-600
">

2026-0001

</td>







<td class="
px-6
py-6
text-gray-600
">

September 18, 2026

</td>







<td class="
px-6
py-6
">


<span class="
bg-green-50
text-green-700
px-3
py-1
rounded-full
text-xs
font-semibold
">

Active

</span>


</td>







<td class="
px-6
py-6
relative
">


<div class="relative">


<button

class="
w-10
h-10
rounded-xl
border
border-gray-200
hover:bg-gray-100
transition
text-gray-600
"

>

⋮

</button>



</div>


</td>




</tr>









<!-- RFID RECORD 2 -->


<tr class="
border-t
hover:bg-gray-50
transition
">


<td class="
px-6
py-6
font-medium
text-gray-700
">

04-B7-44-YY-15

</td>







<td class="
px-6
py-6
">


<div class="
flex
items-center
gap-3
">


<div class="
w-10
h-10
rounded-full
bg-[#101064]
text-white
flex
items-center
justify-center
font-bold
">

M

</div>



<div>


<p class="
font-semibold
text-[#101064]
">

Maria Santos

</p>


<p class="
text-sm
text-gray-500
">

BS Accountancy

</p>


</div>


</div>


</td>







<td class="
px-6
py-6
text-gray-600
">

2026-0002

</td>







<td class="
px-6
py-6
text-gray-600
">

September 18, 2026

</td>







<td class="
px-6
py-6
">


<span class="
bg-green-50
text-green-700
px-3
py-1
rounded-full
text-xs
font-semibold
">

Active

</span>


</td>







<td class="
px-6
py-6
relative
">


<button

class="
w-10
h-10
rounded-xl
border
border-gray-200
hover:bg-gray-100
transition
text-gray-600
"

>

⋮

</button>



</td>






</tr>







</tbody>


</table>


</div>


</div>







</div>


</x-admin-layout>