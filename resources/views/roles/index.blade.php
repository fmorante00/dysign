<x-admin-layout>


<div class="space-y-8">



<!-- HEADER -->

<div>


<h1

class="
text-3xl
font-bold
text-[#101064]
"

>

Roles & Access

</h1>


<p

class="
mt-2
text-gray-500
"

>

Manage user roles and system access privileges.

</p>


</div>









<!-- SUMMARY -->

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

Total Roles

</p>


<h2 class="
text-4xl
font-bold
text-[#D4A017]
mt-2
">

3

</h2>


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

Active Users

</p>


<h2 class="
text-4xl
font-bold
text-[#D4A017]
mt-2
">

30

</h2>


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

Permission Groups

</p>


<h2 class="
text-4xl
font-bold
text-[#D4A017]
mt-2
">

12

</h2>


</div>



</div>









<!-- ROLES TABLE -->

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


<div

class="
p-6
border-b
border-gray-100
"

>


<h2

class="
text-xl
font-semibold
text-[#101064]
"

>

System Roles

</h2>


</div>







<div class="overflow-x-auto">


<table class="w-full text-left">



<thead class="bg-gray-50">


<tr>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Role

</th>



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Description

</th>



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Users

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



<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Action

</th>



</tr>


</thead>







<tbody>





<tr class="border-t">


<td class="
px-6
py-5
font-semibold
text-[#101064]
">

Administrator

</td>


<td class="
px-6
py-5
text-gray-600
">

Full system access and management privileges.

</td>


<td class="
px-6
py-5
text-gray-700
">

1

</td>


<td class="px-6 py-5">

<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Active

</span>


</td>


<td class="px-6 py-5">


<button

class="
px-4
py-2
rounded-lg
bg-[#101064]
text-white
text-sm
hover:bg-[#D4A017]
transition
"

>

Manage Permissions

</button>


</td>


</tr>










<tr class="border-t">


<td class="
px-6
py-5
font-semibold
text-[#101064]
">

Attendance Personnel

</td>


<td class="
px-6
py-5
text-gray-600
">

Handles attendance operations and RFID scanning.

</td>


<td class="
px-6
py-5
text-gray-700
">

5

</td>


<td class="px-6 py-5">

<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Active

</span>


</td>


<td class="px-6 py-5">


<button

class="
px-4
py-2
rounded-lg
bg-[#101064]
text-white
text-sm
hover:bg-[#D4A017]
transition
"

>

Manage Permissions

</button>


</td>


</tr>









<tr class="border-t">


<td class="
px-6
py-5
font-semibold
text-[#101064]
">

Registrar Staff

</td>


<td class="
px-6
py-5
text-gray-600
">

Manages official student records and information.

</td>


<td class="
px-6
py-5
text-gray-700
">

3

</td>


<td class="px-6 py-5">

<span class="
px-3
py-1
rounded-full
bg-green-100
text-green-700
text-xs
font-semibold
">

Active

</span>


</td>


<td class="px-6 py-5">


<button

class="
px-4
py-2
rounded-lg
bg-[#101064]
text-white
text-sm
hover:bg-[#D4A017]
transition
"

>

Manage Permissions

</button>


</td>


</tr>







</tbody>



</table>


</div>



</div>






</div>


</x-admin-layout>