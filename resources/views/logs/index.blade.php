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

Activity Logs

</h1>


<p

class="
mt-2
text-gray-500
"

>

Monitor system activities and user actions.

</p>


</div>









<!-- FILTER AREA -->


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


<div

class="
flex
flex-col
md:flex-row
gap-4
justify-between
"

>



<div>


<h2

class="
text-lg
font-semibold
text-[#101064]
"

>

System Activity History

</h2>


<p

class="
text-sm
text-gray-500
mt-1
"

>

Audit trail of DySign operations.

</p>


</div>







<div

class="
flex
gap-3
"

>


<input

type="text"

placeholder="Search activity..."

class="
border
rounded-xl
px-4
py-2
focus:outline-none
"

>



<select

class="
border
rounded-xl
px-4
py-2
"

>

<option>

All Modules

</option>


<option>

Users

</option>


<option>

Students

</option>


<option>

RFID

</option>


<option>

Events

</option>


</select>



</div>



</div>



</div>









<!-- LOG TABLE -->


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


<div class="overflow-x-auto">


<table

class="
w-full
text-left
"

>


<thead class="bg-gray-50">


<tr>


<th

class="
px-6
py-4
text-xs
uppercase
text-gray-500
"

>

Date & Time

</th>




<th

class="
px-6
py-4
text-xs
uppercase
text-gray-500
"

>

User

</th>





<th

class="
px-6
py-4
text-xs
uppercase
text-gray-500
"

>

Action

</th>





<th

class="
px-6
py-4
text-xs
uppercase
text-gray-500
"

>

Module

</th>





<th

class="
px-6
py-4
text-xs
uppercase
text-gray-500
"

>

Status

</th>




</tr>


</thead>








<tbody>






<tr class="border-t">


<td class="
px-6
py-5
text-sm
text-gray-600
">

Sept 19, 2026
<br>
08:30 AM

</td>



<td class="
px-6
py-5
font-semibold
text-[#101064]
">

System Administrator

</td>



<td class="
px-6
py-5
text-gray-700
">

Created personnel account

</td>



<td class="
px-6
py-5
">

<span class="
px-3
py-1
rounded-full
bg-blue-100
text-blue-700
text-xs
font-semibold
">

Users

</span>

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

Success

</span>

</td>


</tr>









<tr class="border-t">


<td class="
px-6
py-5
text-sm
text-gray-600
">

Sept 19, 2026
<br>
09:15 AM

</td>



<td class="
px-6
py-5
font-semibold
text-[#101064]
">

John Reyes

</td>



<td class="
px-6
py-5
text-gray-700
">

Attendance scan recorded

</td>



<td class="
px-6
py-5
">

<span class="
px-3
py-1
rounded-full
bg-purple-100
text-purple-700
text-xs
font-semibold
">

RFID

</span>

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

Success

</span>

</td>


</tr>









<tr class="border-t">


<td class="
px-6
py-5
text-sm
text-gray-600
">

Sept 19, 2026
<br>
10:00 AM

</td>



<td class="
px-6
py-5
font-semibold
text-[#101064]
">

System Administrator

</td>



<td class="
px-6
py-5
text-gray-700
">

Created new event

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

Events

</span>

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

Success

</span>

</td>


</tr>








<tr class="border-t">


<td class="
px-6
py-5
text-sm
text-gray-600
">

Sept 18, 2026
<br>
04:30 PM

</td>



<td class="
px-6
py-5
font-semibold
text-[#101064]
">

System Administrator

</td>



<td class="
px-6
py-5
text-gray-700
">

Updated student information

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

Students

</span>

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

Success

</span>

</td>


</tr>





</tbody>


</table>


</div>


</div>








</div>


</x-admin-layout>