<x-admin-layout>


<div class="space-y-8">



<!-- HEADER -->

<div>


<h1 class="
text-3xl
font-bold
text-[#101064]
">

Backup Management

</h1>


<p class="
mt-2
text-gray-500
">

Manage system backup status and backup history.

</p>


</div>









<!-- BACKUP STATUS -->

<div

class="
grid
grid-cols-1
md:grid-cols-3
gap-6
"

>



<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>


<p class="
text-sm
text-gray-500
">

Last Backup

</p>


<h2 class="
text-xl
font-bold
text-[#101064]
mt-3
">

September 19, 2026

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

05:00 AM

</p>


</div>






<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>


<p class="
text-sm
text-gray-500
">

Backup Status

</p>


<span class="
inline-block
mt-4
px-4
py-2
rounded-full
bg-green-100
text-green-700
text-sm
font-semibold
">

Completed

</span>


</div>






<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
"

>


<p class="
text-sm
text-gray-500
">

Storage Used

</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-3
">

2.4 GB

</h2>


</div>



</div>









<!-- ACTION -->

<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
flex
justify-between
items-center
"

>


<div>


<h2 class="
text-lg
font-semibold
text-[#101064]
">

Create New Backup

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

Generate a new system backup file.

</p>


</div>





<button

class="
px-6
py-3
rounded-xl
bg-[#101064]
text-white
font-semibold
hover:bg-[#D4A017]
transition
"

>

Create Backup

</button>



</div>









<!-- BACKUP HISTORY -->

<div

class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
overflow-hidden
"

>



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

Backup History

</h2>

</div>






<div class="overflow-x-auto">


<table class="
w-full
text-left
">


<thead class="bg-gray-50">


<tr>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Date

</th>



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Time

</th>



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Created By

</th>



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Status

</th>


</tr>


</thead>







<tbody>


<tr class="border-t">


<td class="
px-6
py-5
">

September 19, 2026

</td>


<td class="
px-6
py-5
">

05:00 AM

</td>


<td class="
px-6
py-5
">

System Administrator

</td>


<td class="
px-6
py-5
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

Completed

</span>

</td>


</tr>







<tr class="border-t">


<td class="
px-6
py-5
">

September 18, 2026

</td>


<td class="
px-6
py-5
">

05:00 AM

</td>


<td class="
px-6
py-5
">

System Administrator

</td>


<td class="
px-6
py-5
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

Completed

</span>

</td>


</tr>







<tr class="border-t">


<td class="
px-6
py-5
">

September 17, 2026

</td>


<td class="
px-6
py-5
">

05:00 AM

</td>


<td class="
px-6
py-5
">

System Administrator

</td>


<td class="
px-6
py-5
">

<span class="
px-3
py-1
rounded-full
bg-yellow-100
text-yellow-700
text-xs
font-semibold
">

Processing

</span>

</td>


</tr>





</tbody>


</table>


</div>



</div>







</div>


</x-admin-layout>