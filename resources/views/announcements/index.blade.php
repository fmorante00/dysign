<x-admin-layout>


<div class="space-y-8">



<!-- HEADER -->

<div>

<h1 class="
text-3xl
font-bold
text-[#101064]
">

Event Announcements

</h1>


<p class="
mt-2
text-gray-500
">

Create and manage event announcements, reminders, and notifications for students and authorized personnel.

</p>


</div>









<!-- SUMMARY CARDS -->


<div class="
grid
grid-cols-1
md:grid-cols-4
gap-6
">



<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Total Announcements

</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

24

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Sent Notifications

</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

1,245

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Upcoming Reminders

</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

8

</h2>


</div>







<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
">


<p class="
text-sm
text-gray-500
">

Recipients

</p>


<h2 class="
text-3xl
font-bold
text-[#D4A017]
mt-2
">

2,540

</h2>


</div>



</div>









<!-- CREATE ANNOUNCEMENT -->


<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
p-6
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

Create Announcement

</h2>


<p class="
text-sm
text-gray-500
mt-1
">

Send event information to selected recipients.

</p>


</div>


<button

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

New Announcement

</button>


</div>








<div class="
grid
grid-cols-1
md:grid-cols-2
gap-6
">



<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Select Event

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
Freshmen Orientation 2026
</option>


<option>
Leadership Training Seminar
</option>


<option>
College Assembly
</option>


</select>


</div>







<div>


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Recipients

</label>


<select

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

>


<option>
Expected Participants
</option>


<option>
Assigned Personnel
</option>


<option>
All Active Students
</option>


<option>
Specific Students
</option>


</select>


</div>



</div>








<div class="mt-6">


<label class="
block
text-sm
font-medium
text-gray-700
mb-2
">

Announcement Message

</label>


<textarea

rows="4"

class="
w-full
rounded-xl
border
border-gray-300
px-4
py-3
"

placeholder="Enter event announcement..."

></textarea>


</div>






<div class="mt-6 flex justify-end">


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

Send Announcement

</button>


</div>



</div>









<!-- NOTIFICATION HISTORY -->


<div class="
bg-white
rounded-2xl
border
border-gray-100
shadow-sm
overflow-hidden
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

Notification History

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

Event

</th>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Recipients

</th>


<th class="
px-6
py-4
text-xs
uppercase
text-gray-500
">

Date Sent

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


<td class="px-6 py-5">

Freshmen Orientation 2026

</td>


<td class="px-6 py-5">

BSIT First Year Students

</td>


<td class="px-6 py-5">

September 20, 2026

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

Sent

</span>


</td>


</tr>





<tr class="border-t">


<td class="px-6 py-5">

Leadership Training Seminar

</td>


<td class="px-6 py-5">

Assigned Personnel

</td>


<td class="px-6 py-5">

September 22, 2026

</td>


<td class="px-6 py-5">


<span class="
px-3
py-1
rounded-full
bg-blue-100
text-blue-700
text-xs
font-semibold
">

Scheduled

</span>


</td>


</tr>



</tbody>


</table>


</div>


</div>








</div>


</x-admin-layout>